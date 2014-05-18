<?php

class LogController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'log';
    }

    public function indexAction()
    {
        return $this->forwardUnderConstruction();
    }

    public function walletAction()
    {
        return $this->forwardUnderConstruction();
    }

    public function hcoinAction()
    {
        return $this->forwardUnderConstruction();
    }
}

