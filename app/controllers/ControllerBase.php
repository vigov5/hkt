<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends BController
{
    /**
     * @var Users $current_user
     */
    public $current_user = null;

    public function initialize()
    {
        $this->tag->setTitle('Framgia Hyakkaten');
        $this->getCurrentUser();
        $this->view->current_page = '';
        if (!$this->current_user) {
            $this->view->login_form = new LoginForm();
        }
    }

    protected function getCurrentUser()
    {
        $current_user = $this->auth->getUser();
        $this->current_user = $current_user;
        $this->view->current_user = $current_user;
    }
}
