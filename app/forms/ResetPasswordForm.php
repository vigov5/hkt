<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class ResetPasswordForm extends Form
{

    public function initialize()
    {
        // New Password
        $new_password = new Password('new_password', ['placeholder' => 'New Password']);
        $new_password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            )),
            new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            )),
            new Confirmation(array(
                'message' => 'Password doesn\'t match confirmation',
                'with' => 'comfirm_password'
            ))
        ));
        $this->add($new_password);

        // Confirm Password
        $confirm_password = new Password('comfirm_password', ['placeholder' => 'Comfirm New Password']);
        $confirm_password->addValidators(array(
            new PresenceOf(array(
                'message' => 'The confirmation password is required'
            ))
        ));
        $this->add($confirm_password);
    }
}
