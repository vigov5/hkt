<?php

class SellerContrller extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'seller';
    }

    public function indexAction()
    {

    }
}