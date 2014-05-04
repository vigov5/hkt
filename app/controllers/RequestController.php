<?php

class RequestController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'request';
    }

    public function sentRequestsAction($type = '')
    {
        if ($type === 'all') {
            $this->view->requests = $this->current_user->getSentRequests(['order' => 'id desc']);
            $this->setPrevUrl("request/sentrequests/all");
        } else {
            $type = null;
            $this->view->requests = $this->current_user->getSentRequests(
                [
                    'conditions' => 'status=' . Requests::STATUS_SENT,
                    'order' => 'id desc'
                ]
            );
            $this->setPrevUrl("request/sentrequests");
        }
        $this->view->type = $type;
    }

    public function receivedRequestsAction($type = '')
    {

        if ($type === 'all') {
            $this->view->requests = $this->current_user->getAllReceivedRequests(true);
            $this->setPrevUrl("request/receivedrequests/all");
        } else {
            $type = null;
            $this->view->requests = $this->current_user->getAllReceivedRequests();
            $this->setPrevUrl("request/receivedrequests");
        }
        $this->view->type = $type;
    }

    public function cancelAction($request_id)
    {
        if ($request_id === 'all') {
            $count = $this->current_user->changeAllRequestsStatus(Requests::STATUS_CANCEL);
            $this->setFlashSession('success', "$count Request(s) canceled");
        } else {
            $redirect = false;
            $request = Requests::findFirst("id = $request_id");
            if (!$request) {
                $this->flash->error('Request not found');
                $redirect = true;
            }
            if (!$this->current_user->canCancelRequest($request)) {
                $this->flash->error('You do not have the right to cancel this request');
                $redirect = true;
            }
            if ($redirect) {
                $this->forward('index/notFound');
            }
            if ($request->isStatusSent()) {
                $request->beCanceled();
                $this->setFlashSession('success', 'Request canceled');
            } else {
                $this->setFlashSession('error', 'Request can not be canceled');
            }
        }

        $this->redirectToPrevUrl();
    }

    public function acceptAction($request_id)
    {
        if ($request_id === 'all') {
            $count = $this->current_user->acceptAllRequests();
            $this->setFlashSession('success', "$count Request(s) accepted");
        } else {
            $redirect = false;
            $request = Requests::findFirst("id = $request_id");
            if (!$request) {
                $this->flash->error('Request not found');
                $redirect = true;
            }
            if (!$this->current_user->canAcceptRequest($request)) {
                $this->flash->error('You do not have the right to accept this request');
                $redirect = true;
            }
            if ($redirect) {
                $this->forward('index/notFound');
            }
            if ($request->isStatusSent()) {
                $request->beAccepted();
                $this->setFlashSession('success', 'Request accepted');
            } else {
                $this->setFlashSession('error', 'Request can not be accepted');
            }
        }

        $this->redirectToPrevUrl();
    }

    public function rejectAction($request_id)
    {
        if ($request_id === 'all') {
            $count = $this->current_user->changeAllRequestsStatus(Requests::STATUS_REJECT);
            $this->setFlashSession('success', "$count Request(s) rejected");
        } else {
            $redirect = false;
            $request = Requests::findFirst("id = $request_id");
            if (!$request) {
                $this->flash->error('Request not found');
                $redirect = true;
            }
            if (!$this->current_user->canAcceptRequest($request)) {
                $this->flash->error('You do not have the right to reject this request');
                $redirect = true;
            }
            if ($redirect) {
                $this->forward('index/notFound');
            }
            if ($request->isStatusSent()) {
                $request->beRejected($this->current_user->id);
                $this->setFlashSession('success', 'Request rejected');
            } else {
                $this->setFlashSession('error', 'Request can not be rejected');
            }
        }

        $this->redirectToPrevUrl();
    }
}