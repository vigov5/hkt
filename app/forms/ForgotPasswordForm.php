<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

class ForgotPasswordForm extends Form
{

    public function initialize()
    {
        $email = new Text('email', array(
            'placeholder' => 'Email or Username'
        ));

        $email->setLabel('Email/Username');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The e-mail is required'
            )),
        ));
        $this->add($email);
    }
}
