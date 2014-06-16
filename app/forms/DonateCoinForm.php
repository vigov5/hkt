<?php
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Identical;

class DonateCoinForm extends Form
{

    public function initialize($user)
    {
        $target_user = new Text('target_user');
        $target_user->setLabel('Target User');

        $target_user->addValidators([
            new PresenceOf([
                'message' => 'The target user is required'
            ]),
        ]);
        $this->add($target_user);
        $items = new Hidden('target_user_id');
        $this->add($items);

        $amount = new Numeric('amount');
        $amount->setLabel('Amount');

        $amount->addValidators([
            new PresenceOf([
                'message' => 'Coin amount is required'
            ]),
            new Between([
                'minimum' => 0,
                'maximum' => $user->hcoin,
                'message' => 'The coin amount must be between 0 and current HCoin'
            ]),
        ]);
        $this->add($amount);

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
