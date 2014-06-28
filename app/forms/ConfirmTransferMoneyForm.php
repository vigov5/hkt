<?php
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;

class ConfirmTransferMoneyForm extends Form
{

    public function initialize()
    {
        $data = new Text('data');
        $data->addValidator(new PresenceOf([
            'message' => 'Please select your action'
        ]));
        $this->add($data);

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
                'value' => $this->security->getSessionToken(),
                'message' => 'Cross-Site Request Forgery Detected !'
            ]));
        $this->add($csrf);
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
