<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ContactController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'faq';
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        return $this->forwardUnderConstruction();
    }
}
