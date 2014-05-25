<?php

class UserController extends ControllerBase
{

    const USERS_PER_PAGE = 100;
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'user';
    }

    public function indexAction($page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        $builder = $this->modelsManager->createBuilder()
            ->from('Users')
            ->orderBy('role desc');

        $paginator = new Phalcon\Paginator\Adapter\QueryBuilder([
            'builder' => $builder,
            'limit' => self::USERS_PER_PAGE,
            'page' => $page
        ]);
        $page = $paginator->getPaginate();

        $this->view->pagination = new Pagination($page, '/user/index');
        $this->view->users = $page->items;
        $this->view->current_page = 'users';
    }

    public function facebookAction()
    {
        $facebook_id = $this->facebook->getUser();
        if (!$facebook_id) {
            $this->flash->error("Invalid Facebook Call.");

            return $this->response->redirect('login');
        }
        try {
            $facebook_user = $this->facebook->api('/me');
        } catch (\FacebookApiException $e) {
            $this->flash->error("Could not fetch your facebook user.");
            //return $this->response->redirect('login');
        }
        $user = Users::findByKey($facebook_user['email']);
        if (!$user) {
            $user = new Users();
            $user->assign([
                    'display_name' => $facebook_user['name'],
                    'username' => $facebook_user['email'],
                    'email' => $facebook_user['email'],
                    'register_type' => Users::REGISTER_FACEBOOK_TYPE,
                    'password' => $this->security->hash(Phalcon\Text::random(Phalcon\Text::RANDOM_ALNUM, 8)),
                ]
            );
            if ($user->save()) {
                $this->auth->authUserById($user->id);
                $user->createRequest(Requests::TYPE_REGISTER);

                return $this->response->redirect('item/onsale');
            } else {
                $this->flash->error('There was an error connecting your facebook user.');
            }
        } else {
            $this->auth->authUserById($user->id);
            $this->auth->saveSuccessLogin($user);

            return $this->response->redirect('item/onsale');
        }
    }

    public function loginAction()
    {
        $form = new LoginForm();

        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check([
                            'email' => $this->request->getPost('email'),
                            'password' => $this->request->getPost('password'),
                            'remember' => $this->request->getPost('remember')
                        ]
                    );

                    return $this->response->redirect('item/onsale');
                }
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
        }
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $this->auth->remove();
        $this->getCurrentUser();
        if ($this->facebook->getUser()) {
            $logout_url = $this->facebook->getLogoutUrl(['next' => $this->url->get('index')]);
            $this->facebook->destroySession();
            $this->facebook->setAccessToken('');

            return $this->response->redirect($logout_url, true);
        }

        return $this->response->redirect('index');
    }

    public function registerAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new Users();

                $user->assign([
                        'username' => $this->request->getPost('username', 'striptags'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->security->hash($this->request->getPost('password')),
                    ]
                );
                if ($user->save()) {
                    $this->auth->authUserById($user->id);
                    $user->createRequest(Requests::TYPE_REGISTER);

                    return $this->dispatcher->forward([
                            'controller' => 'index',
                            'action' => 'index'
                        ]
                    );
                }
                $this->flash->error($user->getMessages());
            }
        }
        $this->view->current_page = 'register';
        $this->view->form = $form;
    }

    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = Users::findByKey($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email/username');
                } else {
                    $reset_url =
                        'http://' . $_SERVER['SERVER_NAME'] . "/user/resetpassword/{$user->email}/{$user->secret_key}";
                    $this->mail->send(
                        $user->email,
                        'HKT Password Recovery',
                        'reset',
                        ['reset_url' => $reset_url]
                    );
                    $this->forward('index');
                    $this->flash->success('Success! Please check your messages for an email reset password');
                    $this->forward('index/index');
                }
            }
        }
        $this->view->current_page = 'forgotpassword';
        $this->view->form = $form;
    }

    public function resetPasswordAction($email = '', $secret_key = '')
    {
        $user = Users::findFirstByEmail($email);
        if (!$user || $user->secret_key != $secret_key) {
            $this->response->redirect('');
        }
        $form = new ResetPasswordForm();

        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user->updatePassword($this->request->getPost('new_password'));
                $user->updateSecretKey();
                $this->flash->success('Your password was successfully changed');
                $this->auth->authUserById($user->id);

                return $this->forward('index/index');
            }
        }
        $this->view->email = $email;
        $this->view->secret_key = $secret_key;
        $this->view->form = $form;
    }

    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();
        if ($this->request->isPost()) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                if ($this->current_user->validatePassword($this->request->getPost('password'))) {
                    $this->current_user->updatePassword($this->request->getPost('new_password'));
                    $this->flash->success('Your password was successfully changed');
                    Phalcon\Tag::resetInput();
                } else {
                    $this->flash->error('Current password is incorrect');
                }
            }
        }
        $this->view->form = $form;
    }

    public function viewAction($id = null)
    {
        if (!$id) {
            $user = $this->current_user;
        } else {
            $user = Users::findFirstById($id);
            if (!$user) {
                $user = $this->current_user;
            }
        }

        $this->view->user = $user;
    }

    public function changeDisplayNameAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $user_id = $this->request->getPost('user_id', 'int');
            $display_name = $this->request->getPost('display_name', 'striptags');
            if ($user_id != $this->current_user->id) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid User',
                ];
            } else {
                $this->current_user->changeDisplayName($display_name);
                $response = [
                    'status' => 'success',
                    'message' => 'Display name changed!',
                    'display_name' => $this->current_user->getUserDisplayName(),
                ];
            }
            echo json_encode($response);
            return ;
        }
        $this->forwardNotFound();
    }

    public function changePlaceAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $place = $this->request->getPost('place', 'int');
            $user_id = $this->request->getPost('user_id', 'int');
            if (!isset(Users::$place_value[$place])) {
                $response = [
                    'status' => 'fail',
                    'message' => 'Invalid Place',
                ];
            } else {
                $user = Users::findFirstById($user_id);
                if ($user_id != $this->current_user->id && !$this->current_user->isRoleOver(Users::ROLE_ADMIN) && !$user) {
                    $response = [
                        'status' => 'fail',
                        'message' => 'Invalid User',
                    ];
                } else {
                    $user->changePlace($place);
                    $response = [
                        'status' => 'success',
                        'message' => 'Place changed!',
                    ];
                }
            }
            echo json_encode($response);
            return ;
        }
        $this->forwardNotFound();
    }

    public function favoriteAction()
    {
        $this->forwardUnderConstruction();
    }

    public function donateAction()
    {
        $this->forwardUnderConstruction();
    }
}

