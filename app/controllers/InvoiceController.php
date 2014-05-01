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
        if ($type == Invoices::TYPE_SENT) {
            $this->view->invoices = $this->current_user->getSentInvoices(['order' => 'id desc']);
            $this->setPrevUrl("invoice");
        } else {
            $this->setPrevUrl("invoice/index/" . Invoices::TYPE_RECEIVED);
            $this->view->invoices = $this->current_user->getReceivedInvoices(['order' => 'id desc']);
        }

        $this->view->type = $type;
    }

    public function cancelAction($invoice_id)
    {
        //var_dump($this->getPrevUrl()); die();
        $redirect = false;
        $invoice = Invoices::findFirst("id = $invoice_id");
        if (!$invoice) {
            $this->flash->error('Invoice not found');
            $redirect = true;
        }
        if (!$this->current_user->canCancelInvoice($invoice)) {
            $this->flash->error('You do not have the right to cancel this invoice');
            $redirect = true;
        }
        if ($redirect) {
            $this->forward('index/notFound');
        }
        if ($invoice->isStatusSent()) {
            $invoice->beCanceled();
            $this->setFlashSession('success', 'Invoice canceled');
        } else {
            $this->setFlashSession('error', 'Invoice can not be canceled');
        }

        $this->redirectToPrevUrl();
    }

    public function acceptAction($invoice_id)
    {
        $redirect = false;
        $invoice = Invoices::findFirst("id = $invoice_id");
        if (!$invoice) {
            $this->flash->error('Invoice not found');
            $redirect = true;
        }
        if (!$this->current_user->canAcceptInvoice($invoice)) {
            $this->flash->error('You do not have the right to accept this invoice');
            $redirect = true;
        }
        if ($redirect) {
            $this->forward('index/notFound');
        }
        if ($invoice->isStatusSent()) {
            $invoice->beAccepted();
            $this->setFlashSession('success', 'Invoice accepted');
        } else {
            $this->setFlashSession('error', 'Invoice can not be accepted');
        }

        $this->redirectToPrevUrl();
    }

    public function rejectAction($invoice_id)
    {
        $redirect = false;
        $invoice = Invoices::findFirst("id = $invoice_id");
        if (!$invoice) {
            $this->flash->error('Invoice not found');
            $redirect = true;
        }
        if (!$this->current_user->canAcceptInvoice($invoice)) {
            $this->flash->error('You do not have the right to reject this invoice');
            $redirect = true;
        }
        if ($redirect) {
            $this->forward('index/notFound');
        }
        if ($invoice->isStatusSent()) {
            $invoice->beRejected();
            $this->setFlashSession('success', 'Invoice rejected');
        } else {
            $this->setFlashSession('error', 'Invoice can not be rejected');
        }
        $this->redirectToPrevUrl();
    }
}