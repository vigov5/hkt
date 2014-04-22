<?php
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;

class BuyForm extends Form
{

    public function initialize()
    {
        $items = new Hidden('items');
        $this->add($items);

        // CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(new Identical([
                'value' => $this->security->getSessionToken(),
                'message' => 'Cross-Site Request Forgery Detected !'
            ]));
        $this->add($csrf);
    }
}
