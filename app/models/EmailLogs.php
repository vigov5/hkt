<?php


use Phalcon\Mvc\Model\Validator\Email as Email;

class EmailLogs extends BModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $email;

    /**
     *
     * @var string
     */
    public $subject;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(
            new Email(
                array(
                    "field"    => "email",
                    "required" => true,
                )
            )
        );
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'email' => 'email',
            'subject' => 'subject',
            'created_at' => 'created_at'
        );
    }

    public static function createNew($email, $subject)
    {
        $email_log = new EmailLogs();
        $email_log->email = $email;
        $email_log->subject = $subject;
        $email_log->save();
    }
}
