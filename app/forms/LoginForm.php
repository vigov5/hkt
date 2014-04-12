<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class LoginForm extends Form
{

    public function initialize()
    {
        // Email or username
        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));
        $email->setLabel('Email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'The e-mail/username is required'
            )),
            new Email(array(
                'message' => 'The e-mail/username is not valid'
            ))
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

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical(array(
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        )));
        $this->add($csrf);
    }
}
