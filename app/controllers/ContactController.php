<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ContactController extends ControllerBase
{
    public function initialize()
    {
        parent::initialize();
        $this->view->current_page = 'faq';
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $form = new ContactForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $contact = new Contacts();
                $contact->email = $this->request->getPost('email', 'email');
                $contact->subject = $this->request->getPost('subject', 'striptags');
                $contact->content = $this->request->getPost('content', 'striptags');
                $contact->status = 0;
                $contact->save();
                $this->flash->success('Your message has been sent to the Administrators');
                $form->clear();
            }
        }
        $this->view->form = $form;
    }
}
