<?php


use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class User extends \Phalcon\Mvc\Model
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
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var integer
     */
    public $wallet;

    /**
     *
     * @var integer
     */
    public $role;

    /**
     *
     * @var string
     */
    public $secret_key;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $updated_at;

    /**
     *
     * @var string
     */
    public $deleted_at;

    /**
     *
     * @var string
     */
    public $wallet_updated_at;

    const ROLE_UNAUTHORIZED = 0;
    const ROLE_USER = 1;
    const ROLE_MODERATOR = 2;
    const ROLE_ADMIN = 3;
    const ROLE_SUPER_ADMIN = 4;

    public static $user_roles = [
        self::ROLE_UNAUTHORIZED => 'Unauthorized User',
        self::ROLE_USER => 'Normal User',
        self::ROLE_MODERATOR => 'MODERATOR',
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_SUPER_ADMIN => 'Super Administrator',
    ];

    public function getRoleValue()
    {
        if (isset(self::$user_roles[$this->role])) {
            return self::$user_roles[$this->role];
        }
        return $this->role;
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(new Email([
            'field'    => 'email',
            'required' => true,
        ]));

        $this->validate(new Uniqueness([
            'field' => 'email',
            'message' => 'The email is already registered',
        ]));

        $this->validate(new Uniqueness([
                'field' => 'username',
                'message' => 'The username is already registered',
            ]));

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->setSource('User');

    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'username' => 'username',
            'password' => 'password',
            'email' => 'email',
            'wallet' => 'wallet',
            'role' => 'role',
            'secret_key' => 'secret_key',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at',
            'wallet_updated_at' => 'wallet_updated_at'
        );
    }

    public function getSaveAttributesName()
    {
        return ['username', 'email'];
    }
}
