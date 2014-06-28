<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class FaqController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'faq';
    }

    /**
     * Index action
     */
    public function indexAction($lang = Faqs::LANG_EN)
    {
        $faqs = Faqs::find([
            'conditions' => "lang = $lang",
            'order' => 'priority, id asc',
        ]);
        $this->view->faqs = $faqs;
    }

    /**
     * Creates a new item
     */
    public function createAction()
    {
        $faq = new Faqs();
        $errors = [];
        if ($this->request->isPost()) {
            $faq->load($_POST);
            $faq->created_by = $this->current_user->id;

            if ($faq->save()) {
                $this->flash->success('New Faq created !');

                return $this->forward('faq');
            } else {
                $this->setDefault($faq);
            }
        }
        $this->view->faq = $faq;
        $this->view->form = new BForm($faq, $errors);
    }

    /**
     * Saves a item edited
     *
     */
    public function updateAction($id)
    {
        $faq = Faqs::findFirst($id);
        if (!$faq) {
            $this->flash->error('Faq was not found');

            return $this->forward('faq');
        }

        $this->setDefault($faq);
        if ($this->request->isPost()) {
            $faq->load($_POST);
            if ($faq->save()) {
                $this->flash->success('Faq was updated successfully');

                return $this->forward('faq');
            }
        }
        $this->setDefault($faq);
        $this->view->faq = $faq;
        $this->view->form = new BForm($faq);
    }
}
