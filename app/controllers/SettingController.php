<?php

class SettingController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'setting';
    }

    public function indexAction()
    {

    }

    public function aclAction()
    {

        if ($this->request->isPost()) {
            $this->acl->rebuild();
            $this->flash->success('ACL Reloaded!');
            $this->forward('setting');
        } else {
            $this->response->redirect('setting');
        }
    }
}

