<?php

class InvoiceController extends ControllerBase
{
    public function indexAction($type=Invoices::STATUS_SENT)
    {
        if ($type === Invoices::STATUS_SENT) {
            $this->view->invoices = $this->current_user->sentInvoices;
        } else {
            $this->view->invoices = $this->current_user->receivedInvoices;
        }
        $this->view->type = $type;

    }
}