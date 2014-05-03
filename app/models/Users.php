<?php


use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Security;

class Users extends BModel
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
    const ROLE_USER = 10;
    const ROLE_MODERATOR = 20;
    const ROLE_ADMIN = 30;
    const ROLE_SUPER_ADMIN = 40;

    public static $user_roles = [
        self::ROLE_UNAUTHORIZED => 'Unauthorized User',
        self::ROLE_USER => 'Normal User',
        self::ROLE_MODERATOR => 'Moderator',
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

    public static function getUpperRoles($base_role)
    {
        $upper_roles = [];
        foreach (self::$user_roles as $role => $role_name) {
            if ($role >= $base_role) {
                $upper_roles[$role] = $role_name;
            }
        }
        return $upper_roles;
    }

    public function isUnauthorized()
    {
        return $this->role == self::ROLE_UNAUTHORIZED;
    }

    public function isUser()
    {
        return $this->role == self::ROLE_USER;
    }

    public function isModerator()
    {
        return $this->role == self::ROLE_MODERATOR;
    }

    public function isAdmin()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function isSuperAdmin()
    {
        return $this->role == self::ROLE_SUPER_ADMIN;
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
        parent::initialize();
		$this->setSource('users');
        $this->keepSnapshots(true);
        $this->hasMany('id', 'Items', 'created_by');
        $this->hasMany('id', 'Invoices', 'to_user_id', ['alias' => 'receivedInvoices']);
        $this->hasMany('id', 'Invoices', 'from_user_id', ['alias' => 'sentInvoices']);
        $this->hasMany('id', 'Requests', 'to_user_id', ['alias' => 'receivedRequests']);
        $this->hasMany('id', 'Requests', 'from_user_id', ['alias' => 'sentRequests']);
        $this->hasMany('id', 'ItemUsers', 'user_id');
        $this->hasManyToMany('id', 'ItemUsers', 'user_id', 'item_id', 'Items', 'id', ['alias' => 'saleItems']);
        $this->hasMany('id', 'WalletLogs', 'user_id', ['alias' => 'walletLogs']);
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

    /**
     * @return array Save Attributes name
     */
    public function getSaveAttributesName()
    {
        return ['username', 'email'];
    }

    /**
     * Before create action
     */
    public function beforeCreate()
    {
        $this->role = self::ROLE_UNAUTHORIZED;
        $this->wallet = 0;
        $this->secret_key = Keygen::generateKey();
    }

    /**
     * Before save action
     */
    public function beforeSave()
    {
        if ($this->hasChanged('wallet')) {
            $snapshot = $this->getSnapshotData();
            $wallet_log = new WalletLogs();
            $wallet_log->user_id = $this->id;
            $wallet_log->wallet_before = $snapshot['wallet'];
            $wallet_log->wallet_after = $this->wallet;
            $this->setSnapshotData(['wallet' => $this->wallet]);
            return $wallet_log->save();
        }
        return true;
    }

    /**
     * Find by email or username
     * @param string $key
     * @return Users|null
     */
    public static function findByKey($key)
    {
        $user = self::findFirstByEmail($key);
        if (!$user) {
            $user = self::findFirstByUsername($key);
        }
        return $user;
    }

    /**
     * Update secret_key field
     * @param bool $save
     * @return bool
     */
    public function updateSecretKey($save=true)
    {
        $this->secret_key = Keygen::generateKey();
        if ($save) {
            return $this->save();
        }
        return true;
    }

    /**
     * Validate password
     * @param string $raw_pass
     * @return bool
     */
    public function validatePassword($raw_pass)
    {
        $security = new Security();
        return $security->checkHash($raw_pass, $this->password);
    }

    /**
     * Update password field
     * @param string $raw_pass
     * @param bool $save
     * @return bool
     */
    public function updatePassword($raw_pass, $save=true)
    {
        $security = new Security();
        $this->password = $security->hash($raw_pass);
        if ($save) {
            return $this->save();
        }
        return true;
    }

    /**
     * Check whether user can edit an item or not
     * @param Items|int $item
     * @return bool
     */
    public function canEditItem($item)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }
        if ($item instanceof Items) {
            return $this->id === $item->created_by;
        } else {
            $item_object = Items::findFirst(['id' => $item]);
            return $this->id === $item_object->created_by;
        }
    }

    /**
     * Check user's wallet
     * @param int $threshold
     * @return bool
     */
    public function checkWallet($threshold)
    {
        return $this->wallet >= $threshold;
    }

    /**
     * Minus user's wallet
     * @param int $amout
     * @return bool
     */
    public function minusWallet($amout) {
        if ($this->checkWallet($amout)) {
            $this->wallet -= $amout;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * @param ItemUsers $item_user
     * @param int $item_count
     * @return bool
     */
    public function createInvoiceToUser($item_user, $item_count=1)
    {
        $invoice = new Invoices();
        $invoice->from_user_id = $this->id;
        $invoice->to_user_id = $item_user->user_id;
        $invoice->item_id = $item_user->item_id;
        $invoice->item_count = $item_count;
        $price = $item_count * $item_user->getSalePrice();
        $invoice->price = $price;
        $this->minusWallet($price);
        if (!$invoice->save()) {
            return false;
        }
        return true;
    }

    /**
     * @param int $type
     * @param null|int $item_id
     * @param int $to_user_id
     * @return bool
     */
    public function createRequest($type, $item_id=null, $to_user_id=0)
    {
        $request = new Requests();
        $request->from_user_id = $this->id;
        $request->type = $type;
        if ($item_id) {
            $request->item_id = $item_id;
        }
        $request->to_user_id = $to_user_id;
        if (!$request->save()) {
            return false;
        }
        return true;
    }

    /**
     * Check whether user can see no-desination requests or not
     * Normally, admin and super admin can see/update these requests
     * @return bool
     */
    public function canAccessNoDestinationRequests()
    {
        return ($this->isAdmin() || $this->isSuperAdmin());
    }

    /**
     * Get all requests that are sent to this user
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getAllReceivedRequests()
    {
        if ($this->canAccessNoDestinationRequests()) {
            return Requests::find([
                'conditions' => 'to_user_id = :to_user_id: OR to_user_id = 0',
                'bind' => ['to_user_id' => $this->id],
                'order' => 'id DESC',
            ]);
        }
        return $this->getReceivedRequests();
    }

    /**
     * @param int $amount
     */
    public function increaseWallet($amount)
    {
        $this->wallet += $amount;
        $this->save();
    }

    /**
     * Check whether this user can cancel the invoice or not
     * @param Invoices $invoice
     * @return bool
     */
    public function canCancelInvoice($invoice)
    {
        return $invoice->from_user_id == $this->id;
    }

    /**
     * Check whether this user can accept/cancel the invoice or not
     * @param Invoices $invoice
     * @return bool
     */
    public function canAcceptInvoice($invoice)
    {
        return $invoice->to_user_id == $this->id;
    }

    public function cancelAllInvoices()
    {
        $invoices = $this->getSentInvoices(['conditions' => 'status='.Invoices::STATUS_SENT]);
        foreach ($invoices as $invoice) {
            $invoice->beCanceled();
        }
        return count($invoices);
    }

    public function acceptAllInvoices()
    {
        $invoices = $this->getReceivedInvoices(['conditions' => 'status='.Invoices::STATUS_SENT]);
        foreach ($invoices as $invoice) {
            $invoice->beAccepted();
        }
        return count($invoices);
    }

    public function rejectAllInvoices()
    {
        $invoices = $this->getReceivedInvoices(['conditions' => 'status='.Invoices::STATUS_SENT]);
        foreach ($invoices as $invoice) {
            $invoice->beRejected();
        }
        return count($invoices);
    }
}
