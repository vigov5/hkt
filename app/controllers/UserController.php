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

                $user->assign([
                        'username' => $this->request->getPost('username', 'striptags'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->security->hash($this->request->getPost('password')),
                    ]);
                if ($user->save()) {
                    $this->auth->authUserById($user->id);
                    return $this->dispatcher->forward([
                        'controller' => 'index',
                        'action' => 'index'
                    ]);
                }
                $this->flash->error($user->getMessages());
            }
        }

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
                $user = User::findByKey($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email/username');
                } else {
                    $reset_url = 'http://' . $_SERVER['SERVER_NAME'] . "/user/resetpassword/{$user->email}/{$user->secret_key}";
                    $this->mail->send(
                        [$user->email => $user->username],
                        'HKT Password Recovery',
                        'reset',
                        ['reset_url' => $reset_url]
                    );
                    $this->forward('index');
                    $this->flash->success('Success! Please check your messages for an email reset password');
                    return;
                }
            }
        }
        $this->view->form = $form;
    }

    public function resetPasswordAction($email='', $secret_key='')
    {
        $user = User::findFirst(['email' => $email]);
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
}

