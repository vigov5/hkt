<?php

class InvoiceController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'wallet';
    }

    public function sentInvoicesAction($type = '')
    {
        if ($type === 'all') {
            $this->view->invoices = $this->current_user->getSentInvoices(['order' => 'id desc']);
            $this->setPrevUrl("invoice/sentinvoices/all");
        } else {
            $type = null;
            $this->view->invoices =
                $this->current_user->getSentInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT,
                        'order' => 'id desc'
                    ]
                );
            $this->setPrevUrl("invoice/sentinvoices");
        }
        $this->view->type = $type;
    }

    public function receivedInvoicesAction($type = '')
    {
        if ($type === 'all') {
            $this->view->invoices = $this->current_user->getReceivedInvoices(['order' => 'id desc']);
            $this->setPrevUrl("invoice/receivedinvoices/all");
        } else {
            $type = null;
            $this->view->invoices =
                $this->current_user->getReceivedInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT,
                        'order' => 'id desc'
                    ]
                );
            $this->setPrevUrl("invoice/receivedinvoices");
        }
        $this->view->type = $type;
    }

    public function cancelAction($invoice_id)
    {
        if ($invoice_id === 'all') {
            $count = $this->current_user->cancelAllInvoices();
            $this->setFlashSession('success', "$count Invoice(s) canceled");
        } else {
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
        }

        $this->redirectToPrevUrl();
    }

    public function acceptAction($invoice_id)
    {
        if ($invoice_id === 'all') {
            $count = $this->current_user->acceptAllInvoices();
            $this->setFlashSession('success', "$count Invoice(s) accepted");
        } else {
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
        }

        $this->redirectToPrevUrl();
    }

    public function rejectAction($invoice_id)
    {
        if ($invoice_id === 'all') {
            $count = $this->current_user->rejectAllInvoices();
            $this->setFlashSession('success', "$count Invoice(s) rejected");
        } else {
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
        }

        $this->redirectToPrevUrl();
    }
}