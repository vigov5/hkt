<?php

class InvoiceController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'wallet';
    }

    public function indexAction($type=Invoices::TYPE_SENT)
    {
        if ($type === Invoices::TYPE_SENT) {
            $this->view->invoices = $this->current_user->getSentInvoices(['order' => 'id desc']);
        } else {
            $this->view->invoices = $this->current_user->getReceivedInvoices(['order' => 'id desc']);
        }
        $this->view->type = $type;

    }
}