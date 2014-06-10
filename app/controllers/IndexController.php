<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'index';
    }

    public function indexAction()
    {
        if ($this->current_user) {
            return $this->forward('shop/open');
        }
    }

    public function notFoundAction()
    {

    }

    public function underConstructionAction()
    {

    }
}

