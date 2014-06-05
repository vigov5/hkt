<?php


use Phalcon\Mvc\Model\Validator\Email as Email;

class Contacts extends BModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
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
    public $content;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'email' => 'email',
            'subject' => 'subject',
            'content' => 'content',
            'status' => 'status',
            'created_at' => 'created_at'
        );
    }

}
