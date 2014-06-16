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
     * @var string
     */
    public $display_name;

    /**
     *
     * @var integer
     */
    public $wallet;

    /**
     *
     * @var integer
     */
    public $hcoin;

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
     * @var integer
     */
    public $register_type;

    /**
     *
     * @var integer
     */
    public $place;

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

    const REGISTER_NORMAL_TYPE = 0;
    const REGISTER_FACEBOOK_TYPE = 1;

    const PLACE_13_FLOOR = 0;
    const PLACE_31_FLOOR = 1;

    public static $user_roles = [
        self::ROLE_UNAUTHORIZED => 'Unauthorized User',
        self::ROLE_USER => 'Normal User',
        self::ROLE_MODERATOR => 'Moderator',
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_SUPER_ADMIN => 'Super Administrator',
    ];

    public static $place_value = [
        self::PLACE_13_FLOOR => '13th Floor',
        self::PLACE_31_FLOOR => '31st Floor',
    ];

    public function getRoleValue()
    {
        if (isset(self::$user_roles[$this->role])) {
            return self::$user_roles[$this->role];
        }

        return $this->role;
    }

    public static $register_types = [
        self::REGISTER_NORMAL_TYPE => 'NORMAL',
        self::REGISTER_FACEBOOK_TYPE => 'FACEBOOK',
    ];

    public function getRegisterTypeValue()
    {
        if (isset(self::$register_types[$this->register_type])) {
            return self::$register_types[$this->register_type];
        }

        return $this->register_type;
    }

    public function getPlaceValue()
    {
        if (isset(self::$place_value[$this->place])) {
            return self::$place_value[$this->place];
        }

        return $this->place;
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

    public static function checkAuthorized($role)
    {
        if ($role === 'Unauthorized User' || (is_numeric($role) && $role == self::ROLE_UNAUTHORIZED)) {
            return false;
        }
        return true;
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

    public function isRoleOver($role)
    {
        return $this->role >= $role;
    }

    public function changeRole($role)
    {
        $this->role = $role;
        $this->save();
    }
    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(new Email([
                'field' => 'email',
                'required' => true,
            ])
        );

        $this->validate(new Uniqueness([
                'field' => 'email',
                'message' => 'The email is already registered',
            ])
        );

        $this->validate(new Uniqueness([
                'field' => 'username',
                'message' => 'The username is already registered',
            ])
        );

        if ($this->username != $this->email) {
            $this->validate(new UsernameValidator(['field' => 'username']));
        }

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
        $this->hasMany('id', 'Items', 'created_by');
        $this->hasMany('id', 'Invoices', 'to_user_id', ['alias' => 'receivedInvoices']);
        $this->hasMany('id', 'Invoices', 'from_user_id', ['alias' => 'sentInvoices']);
        $this->hasMany('id', 'CoinDonations', 'to_user_id', ['alias' => 'receivedDonations']);
        $this->hasMany('id', 'CoinDonations', 'from_user_id', ['alias' => 'sentDonations']);
        $this->hasMany('id', 'Requests', 'to_user_id', ['alias' => 'receivedRequests']);
        $this->hasMany('id', 'Requests', 'from_user_id', ['alias' => 'sentRequests']);
        $this->hasMany('id', 'ItemUsers', 'user_id');
        $this->hasManyToMany('id', 'ItemUsers', 'user_id', 'item_id', 'Items', 'id', ['alias' => 'sellItems']);
        $this->hasManyToMany('id', 'UserShops', 'user_id', 'shop_id', 'Shops', 'id', ['alias' => 'shops']);
        $this->hasMany('id', 'WalletLogs', 'user_id', ['alias' => 'walletLogs']);
        $this->hasMany('id', 'UserShops', 'user_id');
        $this->hasMany('id', 'Favorites', 'user_id');
        $this->hasMany('id', 'Shops', 'created_by', ['alias' => 'ownShops']);
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
            'display_name' => 'display_name',
            'wallet' => 'wallet',
            'hcoin' => 'hcoin',
            'role' => 'role',
            'secret_key' => 'secret_key',
            'register_type' => 'register_type',
            'place' => 'place',
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
        return ['username', 'email', 'display_name'];
    }

    /**
     * Before create action
     */
    public function beforeCreate()
    {
        $this->role = self::ROLE_UNAUTHORIZED;
        $this->wallet = 0;
        $this->hcoin = 0;
        $this->secret_key = Keygen::generateKey();
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
    public function updateSecretKey($save = true)
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
    public function updatePassword($raw_pass, $save = true)
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
     * @param Items $item
     * @return bool
     */
    public function canEditItem($item)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        if ($this->id === $item->created_by) {
            return true;
        }

        return false;
    }

    /**
     * Check whether user can edit an item_user or not
     * @param ItemUsers|int $item_user
     * @return bool
     */
    public function canEditItemuser($item_user)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }
        if ($item_user instanceof ItemUsers) {
            return $this->id === $item_user->user_id;
        } else {
            $item_object = Items::findFirst(['id' => $item_user]);

            return $this->id === $item_object->user_id;
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
    public function minusWallet($amout)
    {
        if ($this->checkWallet($amout)) {
            $this->wallet -= $amout;
            $this->save();

            return $this->wallet;
        }

        return false;
    }

    /**
     * @param ItemUsers $item_user
     * @param int $item_count
     * @return bool
     */
    public function createInvoiceToUser($item_user, $item_count = 1)
    {
        $invoice = new Invoices();
        $invoice->from_user_id = $this->id;
        $invoice->to_user_id = $item_user->user_id;
        $invoice->item_id = $item_user->item_id;
        $invoice->item_type = $item_user->item->type;
        $invoice->item_count = $item_count;
        $invoice->hcoin_receive = $item_count * $item_user->getHCoinReceived();
        $price = $item_count * $item_user->getSalePrice();
        $invoice->price = $price;
        $invoice->real_price = $item_count * $item_user->getRealPrice();
        $wallet_before = $this->wallet;
        if ($item_user->item->isNormalItem()) {
            $this->minusWallet($price);
        }
        $wallet_after = $this->wallet;
        if (!$invoice->save()) {
            return false;
        }
        if ($wallet_before != $wallet_after) {
            WalletLogs::createNew($this->id, $wallet_before, $wallet_after, $invoice->id, nulll, WalletLogs::ACTION_CREATE);
        }

        return true;
    }

    /**
     * @param ItemShops $item_shop
     * @param int $item_count
     * @param string $set_items_id
     * @return bool
     */
    public function createInvoiceToShop($item_shop, $item_count = 1, $set_items_id = '')
    {
        $invoice = new Invoices();
        $invoice->from_user_id = $this->id;
        $invoice->to_shop_id = $item_shop->shop_id;
        $invoice->item_id = $item_shop->item_id;
        $invoice->item_type = $item_shop->item->type;
        $invoice->item_count = $item_count;
        $invoice->hcoin_receive = $item_count * $item_shop->getHCoinReceived();
        $invoice->set_items_id = $set_items_id;
        $price = $item_count * $item_shop->getSalePrice();
        $invoice->price = $price;
        $invoice->real_price = $item_count * $item_shop->getRealPrice();
        $wallet_before = $this->wallet;
        if ($item_shop->item->isNormalItem()) {
            $this->minusWallet($price);
        }
        $wallet_after = $this->wallet;
        if (!$invoice->save()) {
            return false;
        }
        if ($wallet_before != $wallet_after) {
            WalletLogs::createNew($this->id, $wallet_before, $wallet_after, $invoice->id, null, WalletLogs::ACTION_CREATE);
        }

        return true;
    }

    /**
     * @param int $type
     * @param null|int $item_id
     * @param int $to_user_id
     * * @param null|int $from_shop_id
     * @return bool
     */
    public function createRequest($type, $item_id = null, $to_user_id = 0, $from_shop_id = null)
    {
        $request = new Requests();
        $request->from_user_id = $this->id;
        $request->type = $type;
        if ($item_id) {
            $request->item_id = $item_id;
        }
        if ($from_shop_id) {
            $request->from_shop_id = $from_shop_id;
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
     * @param bool $all
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public function getAllReceivedRequests($all = false)
    {
        if ($this->canAccessNoDestinationRequests()) {
            if ($all) {
                $condition = 'to_user_id = :to_user_id: OR to_user_id = 0';
            } else {
                $condition = '(to_user_id = :to_user_id: OR to_user_id = 0) AND status = ' . Requests::STATUS_SENT;
            }
            return Requests::find([
                'conditions' => $condition,
                'bind' => ['to_user_id' => $this->id],
                'order' => 'id DESC',
            ]);
        }
        if ($all) {
            return $this->getReceivedRequests(['order' => 'id desc']);
        } else {
            return $this->getReceivedRequests(['conditions' => 'status=' . Requests::STATUS_SENT, 'order' => 'id desc']);
        }
    }

    /**
     * @param int $amount
     * @return int wallet after
     */
    public function increaseWallet($amount)
    {
        $this->wallet += $amount;
        $this->save();
        return $this->wallet;
    }

    /**
     * @param int $amount
     * @return int hcoin after
     */
    public function increaseHCoin($amount)
    {
        $this->hcoin += $amount;
        $this->save();
        return $this->hcoin;
    }

    /**
     * @param int $amount
     * @return bool
     */
    public function minusHCoin($amount)
    {
        if ($amount > 0 && $this->hcoin >= $amount) {
            $this->hcoin -= $amount;
            $this->save();
            return true;
        }
        return false;
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
     * Check whether this user can cancel the request or not
     * @param Invoices $request
     * @return bool
     */
    public function canCancelRequest($request)
    {
        return $request->from_user_id == $this->id;
    }

    /**
     * Check whether this user can accept/cancel the invoice or not
     * @param Invoices $invoice
     * @return bool
     */
    public function canAcceptInvoice($invoice)
    {
        if ($invoice->to_user_id && $invoice->to_user_id == $this->id) {
            return true;
        }
        if ($invoice->to_shop_id && $invoice->toShop) {
            return $invoice->toShop->checkOwnerOrStaff($this);
        }
        if ($this->isRoleOver(self::ROLE_ADMIN) && ($invoice->item->isDepositItem() || $invoice->item->isWithdrawItem())) {
            return true;
        }
        return false;
    }

    /**
     * Check whether this user can accept/cancel the request or not
     * @param Invoices $request
     * @return bool
     */
    public function canAcceptRequest($request)
    {
        return ($this->canAccessNoDestinationRequests() || $request->to_user_id == $this->id);
    }

    /**
     * Cancel all invoices
     * @return int Number of invoices canceled
     */
    public function cancelAllInvoices()
    {
        $invoices = $this->getSentInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT]);
        foreach ($invoices as $invoice) {
            $invoice->beCanceled($this->id);
        }

        return count($invoices);
    }

    /**
     * Accept all invoices
     * @return int Number of invoices accepted
     */
    public function acceptAllInvoices()
    {
        $invoices = $this->getReceivedInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT]);
        foreach ($invoices as $invoice) {
            $invoice->beAccepted($this->id);
        }

        return count($invoices);
    }

    /**
     * Reject all invoices
     * @return int Number of invoices rejected
     */
    public function rejectAllInvoices()
    {
        $invoices = $this->getReceivedInvoices(['conditions' => 'status=' . Invoices::STATUS_SENT]);
        foreach ($invoices as $invoice) {
            $invoice->beRejected($this->id);
        }

        return count($invoices);
    }

    /**
     * Change All Request Status
     * @param int $status
     * @return int Number of requests that has been changed
     */
    public function changeAllRequestsStatus($status)
    {
        $requests = [];
        if ($status == Requests::STATUS_CANCEL) {
            $requests = $this->getSentRequests(['conditions' => 'status=' . Requests::STATUS_SENT]);
            foreach ($requests as $request) {
                $request->beCanceled();
            }
        } elseif ($status == Requests::STATUS_ACCEPT) {
            $requests = $this->getAllReceivedRequests();
            foreach ($requests as $request) {
                $request->beAccepted($this->id);
            }
        } elseif ($status == Requests::STATUS_REJECT) {
            $requests = $this->getAllReceivedRequests();
            foreach ($requests as $request) {
                $request->beRejected($this->id);
            }
        }

        return count($requests);
    }

    /**
     * @param Items $item
     * @return bool
     */
    public function canCreateBuyItemRequest($item)
    {
        if (!$item->isAvailable() || !$item->isNormalItem()) {
            return false;
        }
        $request = $this->getSentRequests("item_id = {$item->id} AND type = " . Requests::TYPE_USER_SELL_ITEM . ' AND (status = ' . Requests::STATUS_ACCEPT . ' OR status = ' . Requests::STATUS_SENT . ')');

        if (count($request)) {
            return false;
        }
        $item_user = $this->getItemUsers("item_id = {$item->id}");
        if (count($item_user)) {
            return false;
        }

        return true;
    }

    /**
     * Create sell item request
     * @param Items $item
     * @return Requests
     */
    public function createSellItemRequest($item)
    {
        $request = new Requests();
        $request->item_id = $item->id;
        $request->from_user_id = $this->id;
        $request->to_user_id = 0;
        $request->status = Requests::STATUS_SENT;
        $request->type = Requests::TYPE_USER_SELL_ITEM;
        $request->save();
        return $request;
    }

    /**
     * @param Items $item
     * @param Shops $shop
     * @return Requests
     */
    public function createShopSellItemRequest($item, $shop)
    {
        $request = new Requests();
        $request->item_id = $item->id;
        $request->from_user_id = $this->id;
        $request->from_shop_id = $shop->id;
        $request->to_user_id = 0;
        $request->status = Requests::STATUS_SENT;
        $request->type = Requests::TYPE_SHOP_SELL_ITEM;
        $request->save();
        return $request;
    }

    /**
     * Create new item request
     * @param Items $item
     * @return Requests
     */
    public function createNewItemRequest($item)
    {
        $request = new Requests();
        $request->item_id = $item->id;
        $request->from_user_id = $this->id;
        $request->to_user_id = 0;
        $request->status = Requests::STATUS_SENT;
        $request->type = Requests::TYPE_CREATE_ITEM;
        $request->save();
        return $request;
    }

    /**
     * Create new shop request
     * @param Shops $shop
     * @return Requests
     */
    public function createNewShopRequest($shop)
    {
        $request = new Requests();
        $request->from_shop_id = $shop->id;
        $request->from_user_id = $this->id;
        $request->to_user_id = 0;
        $request->status = Requests::STATUS_SENT;
        $request->type = Requests::TYPE_CREATE_SHOP;
        $request->save();
        return $request;
    }

    /**
     * Check whether user can edit a shop or not
     * @param Shops|int $shop
     * @return bool
     */
    public function canEditShop($shop)
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }
        return $shop->checkOwnerOrStaff($this);
    }

    /**
     * @param Shops $shop
     * @return bool
     */
    public function canCreateShopStaffRequest($shop)
    {
        if ($shop->isUnauthorized()) {
            return false;
        }

        if ($shop->checkOwner($this)) {
            return false;
        }

        if ($shop->checkStaff($this)) {
            return false;
        }

        $request = $this->getSentRequests("from_shop_id = {$shop->id} AND type = " . Requests::TYPE_SHOP_STAFF . ' AND status = ' . Requests::STATUS_SENT);

        if (count($request)) {
            return false;
        }

        return true;
    }

    /**
     * @return string Class base on User's role
     */
    public function getClassBaseOnRole()
    {
        switch ($this->role) {
            case self::ROLE_SUPER_ADMIN:
                return 'danger';
            case self::ROLE_ADMIN:
                return 'warning';
            case self::ROLE_MODERATOR:
                return 'primary';
            case self::ROLE_USER:
                return 'success';
            default:
                return 'default';
        }
    }

    /**
     * If user has display_name, return it. Otherwise, return username
     * Also add class to change color base on role
     * @return string Display Name
     */
    public function getUserDisplayName()
    {
        $name = $this->display_name ? $this->display_name : $this->username;
        return '<span class="text-' . $this->getClassBaseOnRole() . '">' . $name . '</span>';
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     */
    public function getGravatar($s = 512, $d = 'mm', $r = 'g', $img = false, $atts = []) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($this->email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Change user's display name
     * @param string $display_name
     */
    public function changeDisplayName($display_name)
    {
        $this->display_name = $display_name;
        $this->save();
    }

    /**
     * Change user's place
     * @param int $place
     */
    public function changePlace($place)
    {
        $this->place = $place;
        $this->save();
    }

    /**
     * @return string User view link
     */
    public function getViewLink()
    {
        return '<a class="no-underline" href="' . "/user/view/{$this->id}" . '">' . $this->getUserDisplayName() . '</a>';
    }

    /**
     * Check whether user can register a target as favorite or not
     * At the moment, return true in all cases
     * @param $target
     * @return bool
     */
    public function canRegisterFavorite($target = null)
    {
        return true;
    }

    /**
     * Register a favorite (a shop, an item, or an user)
     * @param int $target_id
     * @param int $target_type
     * @return bool
     */
    public function registerFavorite($target_id, $target_type)
    {
        $favorite = new Favorites();
        $favorite->assign([
            'user_id' => $this->id,
            'target_id' => $target_id,
            'target_type' => $target_type,
            'views' => 0,
            'receive_notification' => Favorites::NOTIFICATION_YES,
        ]);
        return $favorite->save();
    }

    /**
     * Get Favorite by target and target type
     * @param BModel|int $target
     * @param null|int $target_type
     * @return null|Favorites
     */
    public function getFavorite($target, $target_type = null)
    {
        $target_id = null;
        if ($target instanceof Shops || $target instanceof Items || $target instanceof Users) {
            $target_id = $target->id;
            $target_type = Favorites::getTargetType($target);
        } elseif (is_numeric($target)) {
            $target_id = $target;
        }
        if (is_numeric($target_id) && is_numeric($target_type)) {
            return Favorites::findFirst("user_id = $this->id AND target_id = $target_id AND target_type = $target_type");
        }
        return null;
    }

    /**
     * Get Favorites list of the user
     * @param int $limit
     * @return Favorites|[] Favorites list
     */
    public function getFavoriteList($limit = 0)
    {
        if ($limit) {
            $this->getFavorites([
                'order' => 'views desc',
                'limit' => $limit,
            ]);
        }
        return $this->getFavorites(['order' => 'views desc']);
    }

    /**
     * Get admin Emails array
     * @return array
     */
    public static function getAdminEmails()
    {
        $users = Users::find('role >= ' . Users::ROLE_ADMIN)->toArray();
        return array_column($users, 'email');
    }

    public function makeHCoinDonation($target_user, $amount){
        $sender_hcoin_before = $this->hcoin;
        $recipient_hcoin_before = $target_user->hcoin;
        if($this->minusHCoin($amount)) {
            $target_user->increaseHCoin($amount);
            $donation = new CoinDonations();
            $donation->from_user_id = $this->id;
            $donation->to_user_id = $target_user->id;
            $donation->hcoin_amount = $amount;
            if (!$donation->save()) {
                return false;
            }
            $sender_hcoin_after = $this->hcoin;
            $recipient_hcoin_after = $target_user->hcoin;
            WalletLogs::createNew(
                $this->id,
                $sender_hcoin_before,
                $sender_hcoin_after,
                null, $donation->id,
                WalletLogs::ACTION_SENT,
                WalletLogs::TYPE_HCOIN
            );
             WalletLogs::createNew(
                $target_user->id,
                $recipient_hcoin_before,
                $recipient_hcoin_after,
                null, $donation->id,
                WalletLogs::ACTION_RECEIVED,
                WalletLogs::TYPE_HCOIN
            );
            return true;
        }
        return false;
    }
}
