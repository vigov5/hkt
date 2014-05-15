<?php

class RequestController extends ControllerBase
{
    const REQUESTS_PER_PAGE = 20;

    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'request';
    }

    public function sentRequestsAction($type = 'restricted', $page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        if ($type === 'all') {
            $requests = $this->current_user->getSentRequests(['order' => 'id desc']);
        } else {
            $type = 'restricted';
            $requests = $this->current_user->getSentRequests(
                [
                    'conditions' => 'status=' . Requests::STATUS_SENT,
                    'order' => 'id desc'
                ]
            );
        }

        $this->setPrevUrl("request/sentrequests/$type/$page");
        $paginator = new \Phalcon\Paginator\Adapter\Model(
            array(
                'data' => $requests,
                'limit' => self::REQUESTS_PER_PAGE,
                'page' => $page,
            )
        );
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/request/sentrequests/$type");
        $this->view->requests = $page->items;
        $this->view->type = $type;
    }

    public function receivedRequestsAction($type = 'restricted', $page = 1)
    {
        $page = intval($page);
        if ($page < 1) {
            $page = 1;
        }
        if ($type === 'all') {
            $requests = $this->current_user->getAllReceivedRequests(true);
        } else {
            $type = 'restricted';
            $requests = $this->current_user->getAllReceivedRequests();
        }

        $this->setPrevUrl("request/receivedrequests/$type/$page");
        $paginator = new \Phalcon\Paginator\Adapter\Model(
            array(
                'data' => $requests,
                'limit' => self::REQUESTS_PER_PAGE,
                'page' => $page,
            )
        );
        $page = $paginator->getPaginate();
        $this->view->pagination = new Pagination($page, "/request/receivedrequests/$type");
        $this->view->requests = $page->items;
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
            $count = $this->current_user->changeAllRequestsStatus(Requests::STATUS_ACCEPT);
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