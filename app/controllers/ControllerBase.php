<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends BController
{
    public function initialize()
    {
        $this->tag->setTitle('Framgia Hyakkaten');
        $this->getCurrentUser();
    }

    protected function getCurrentUser()
    {
        $current_user = $this->auth->getUser();
        $this->persistent->current_user = $current_user;
        $this->view->current_user = $current_user;
    }
}
