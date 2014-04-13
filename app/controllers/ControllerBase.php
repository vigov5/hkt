<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends BController
{
    /**
     * @var User $current_user
     */
    public $current_user = null;

    public function initialize()
    {
        $this->tag->setTitle('Framgia Hyakkaten');
        $this->getCurrentUser();
    }

    protected function getCurrentUser()
    {
        $current_user = $this->auth->getUser();
        $this->current_user = $current_user;
        $this->view->current_user = $current_user;
    }
}
