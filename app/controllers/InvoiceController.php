<?php

class InvoiceController extends ControllerBase
{
    const INVOICES_PER_PAGE = 20;

    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'wallet';
    }

    public function sentInvoicesAction($type = 'restricted', $page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        if ($type === 'all') {
            $invoices = $this->current_user->getSentInvoices(['order' => 'id desc']);
        } else {
            $type = 'restricted';
            $invoices =
                $this->current_user->getSentInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT,
                        'order' => 'id desc'
                    ]
                );
        }
        $this->setPrevUrl("invoice/sentinvoices/$type/$page");
        $paginator = new \Phalcon\Paginator\Adapter\Model([
                'data' => $invoices,
                'limit' => self::INVOICES_PER_PAGE,
                'page' => $page,
            ]
        );
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/invoice/sentinvoices/$type");
        $this->view->invoices = $page->items;
        $this->view->type = $type;
    }

    public function receivedInvoicesAction($type = 'restricted', $page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        if ($type === 'all') {
            $invoices = $this->current_user->getReceivedInvoices(['order' => 'id desc']);
        } else {
            $type = 'restricted';
            $invoices =
                $this->current_user->getReceivedInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT,
                        'order' => 'id desc'
                    ]
                );
        }
        $this->setPrevUrl("invoice/receivedinvoices/$type/$page");
        $paginator = new \Phalcon\Paginator\Adapter\Model([
                'data' => $invoices,
                'limit' => self::INVOICES_PER_PAGE,
                'page' => $page,
            ]
        );
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/invoice/receivedinvoices/$type");
        $this->view->invoices = $page->items;
        $this->view->type = $type;
    }

    public function specialAction($type = 'restricted', $page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        if ($type === 'all') {
            $invoices = Invoices::find([
                    'conditions' => 'item_type = ' . Items::TYPE_DEPOSIT . ' or item_type = ' . Items::TYPE_WITHDRAW,
                    'order' => 'id desc'
                ]
            );
        } else {
            $type = 'restricted';
            $invoices = Invoices::find([
                        'conditions' => '(item_type = ' . Items::TYPE_DEPOSIT . ' or item_type = ' . Items::TYPE_WITHDRAW . ') and status=' . Invoices::STATUS_SENT,
                        'order' => 'id desc'
                    ]
                );
        }
        $this->setPrevUrl("invoice/special/$type/$page");
        $paginator = new \Phalcon\Paginator\Adapter\Model([
                'data' => $invoices,
                'limit' => self::INVOICES_PER_PAGE,
                'page' => $page,
            ]
        );
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/invoice/special/$type");
        $this->view->invoices = $page->items;
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
                $invoice->beCanceled($this->current_user->id);
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
                if ($invoice->beAccepted($this->current_user->id)) {
                    $this->setFlashSession('success', 'Invoice accepted');
                } else {
                    $this->setFlashSession('error', 'Invoice can not be accepted');
                }

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
                $invoice->beRejected($this->current_user->id);
                $this->setFlashSession('success', 'Invoice rejected');
            } else {
                $this->setFlashSession('error', 'Invoice can not be rejected');
            }
        }

        $this->redirectToPrevUrl();
    }

    public function changeStatusAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $error = '';
            $data = [];
            $invoice_id = $this->request->getPost('invoice_id', 'int');
            $status = $this->request->getPost('status', 'int');
            if ($status != Invoices::STATUS_ACCEPT && $status != Invoices::STATUS_CANCEL &&
                $status != Invoices::STATUS_REJECT
            ) {
                $error = 'Invalid Status ' . $status;
            } else {
                $invoice = Invoices::findFirst($invoice_id);
                if ($invoice && $invoice->isStatusSent()) {
                    $error = $invoice->changeStatus($status, $this->current_user);
                    if (!$error) {
                        $data = [
                            'updated_by' => $invoice->updatedBy->getViewLink(),
                            'updated_at' => $invoice->updated_at,
                            'status' => $invoice->status,
                            'status_string' => $invoice->printStatus(),
                        ];
                    }
                } else {
                    $error = 'Invalid Invoice';
                }
            }
            $response = [
                'status' => $error ? 'fail' : 'success',
                'message' => $error,
                'data' => $data,
                'current_user_wallet' => $this->getCurrentUser()->wallet,
            ];
            echo json_encode($response);
            return;
        }

        return $this->forwardNotFound();
    }

    public function changeStatusAllAction()
    {
        if ($this->request->isAjax()) {
            $this->view->disable();
            $error = '';
            $data = [];
            $count = 0;
            $invoices_id = $this->request->getPost('invoices_id', 'int');
            $status = $this->request->getPost('status', 'int');
            if ($status != Invoices::STATUS_ACCEPT && $status != Invoices::STATUS_CANCEL &&
                $status != Invoices::STATUS_REJECT
            ) {
                $error = 'Invalid Status ' . $status;
            } else {
                foreach ($invoices_id as $invoice_id) {
                    $count++;
                    $invoice = Invoices::findFirst($invoice_id);
                    if ($invoice && $invoice->isStatusSent()) {
                        $error = $invoice->changeStatus($status, $this->current_user);
                        if (!$error) {
                            $data[$invoice_id] = [
                                'id' => $invoice_id,
                                'updated_by' => $invoice->updatedBy->username,
                                'updated_at' => $invoice->updated_at,
                                'status' => $invoice->status,
                                'status_string' => $invoice->printStatus(),
                            ];
                        }
                    }
                }
            }
            $response = [
                'status' => $error ? 'fail' : 'success',
                'message' => $error,
                'data' => $data,
                'count' => $count,
                'current_user_wallet' => $this->getCurrentUser()->wallet,
            ];
            echo json_encode($response);
            return;
        }

        return $this->forwardNotFound();
    }

    public function viewAction($id)
    {
        $invoice = Invoices::findFirst($id);
        if (!$invoice) {
            return $this->forwardNotFound();
        }

        $this->view->invoice = $invoice;
    }
}