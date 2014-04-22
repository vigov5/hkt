<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
{

    public function initialize()
    {
        // Email or username
        $email = new Text('email', array(
            'placeholder' => 'Email or Username'
        ));
        $email->setLabel('Email/Username');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The e-mail/username is required'
            )),
        ));
        $this->add($email);

        // Password
        $password = new Password('password', array(
            'placeholder' => 'Password'
        ));
        $password->setLabel('Password');
        $password->addValidator(new PresenceOf(array(
            'message' => 'The password is required'
        )));
        $this->add($password);

        // Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));
        $remember->setLabel('Remember me');
        $this->add($remember);
    }
}
