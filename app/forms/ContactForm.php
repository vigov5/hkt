<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

class ContactForm extends Form
{

    public function initialize()
    {
        $email = new Text('email', [
            'placeholder' => 'Email'
        ]);

        $email->addValidators([
            new PresenceOf([
                'message' => 'The e-mail is required'
            ]),
            new Email([
                'message' => 'The e-mail is not valid'
            ])
        ]);
        $this->add($email);

        $subject = new Text('subject', [
            'placeholder' => 'Subject'
        ]);

        $subject->addValidators([
            new PresenceOf([
                'message' => 'The subject is required'
            ]),
        ]);
        $this->add($subject);

        $content = new TextArea('content', [
            'placeholder' => 'Content'
        ]);

        $content->addValidators([
            new PresenceOf([
                'message' => 'The content is required'
            ]),
        ]);
        $this->add($content);
    }
}
