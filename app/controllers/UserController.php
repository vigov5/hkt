<?php

class UserController extends ControllerBase
{

    public function indexAction()
    {

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
                    ]);
                    return $this->response->redirect('user');
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
        return $this->response->redirect('index');
    }

    public function registerAction()
    {
        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) != false) {

                $user = new User();

                $user->assign(array(
                        'username' => $this->request->getPost('username', 'striptags'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->security->hash($this->request->getPost('password')),
                        'role' => User::ROLE_UNAUTHORIZED,
                        'wallet' => 0,
                    ));

                if ($user->save()) {
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }
                $this->flash->error($user->getMessages());
            }
        }

        $this->view->form = $form;
    }

    public function forgetPasswordAction()
    {

    }

    public function resetPasswordAction()
    {

    }

    public function changePasswordAction()
    {

    }
}

