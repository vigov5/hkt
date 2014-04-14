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

    }

    public function notFoundAction()
    {

    }
}

