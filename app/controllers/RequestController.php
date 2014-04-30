<?php

class RequestController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'request';
    }

    public function indexAction($type=Requests::STATUS_SENT)
    {
        if ($type === Requests::STATUS_SENT) {
            $this->view->requests = $this->current_user->getSentRequests(['order' => 'id desc']);
        } else {
            $this->view->requests = $this->current_user->getAllReceivedRequests();
        }
        $this->view->type = $type;
    }
}