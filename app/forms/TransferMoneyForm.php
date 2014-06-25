<?php
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Between;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\InclusionIn;

class TransferMoneyForm extends Form
{

    public function initialize($user)
    {
        $target_user = new Text('target_user');
        $target_user->setLabel('Recipient');

        $target_user->addValidators([
            new PresenceOf([
                'message' => 'The target user is required'
            ]),
        ]);
        $this->add($target_user);
        $target_user_id = new Hidden('target_user_id');
        $this->add($target_user_id);

        $amount = new Numeric('amount');
        $amount->setLabel('Amount');

        $amount->addValidators([
            new PresenceOf([
                'message' => 'Money amount is required'
            ]),
            new Between([  
                'minimum' => 0,
                'maximum' => $user->wallet,
                'message' => 'The money amount must be between 0 and current balance of wallet'
            ]),
        ]);
        $this->add($amount);
        
        $fee_bearer = new Select("fee_bearer", array(
            MoneyTransfers::SENDER_FEE => 'The <strong>sender</strong> handles the transfer fee.',
            MoneyTransfers::RECIPIENT_FEE => 'The <strong>recipient</strong> handles the transfer fee.',
        ));
        $fee_bearer->setLabel("Transfer Bearer");
        $fee_bearer->addValidators([
            new PresenceOf([
                'message' => 'Please select who handles the transfer fee.'
            ]),
            new InclusionIn([
                'message' => 'The fee bearer must be the sender or the recipient',
                'domain' => [MoneyTransfers::SENDER_FEE, MoneyTransfers::RECIPIENT_FEE]
            ])
        ]);
        $this->add($fee_bearer);

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
