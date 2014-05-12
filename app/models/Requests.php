<?php


class Requests extends BModel
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
    public $from_user_id;

    /**
     *
     * @var integer
     */
    public $to_user_id;

    /**
     *
     * @var integer
     */
    public $from_shop_id;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     * @var integer
     */
    public $item_id;

    /**
     * @var integer
     */
    public $updated_by;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * A new user registered
     */
    const TYPE_REGISTER = 1;

    /**
     * An user want to create an item
     */
    const TYPE_CREATE_ITEM = 2;

    /**
     * An user want to create an shop
     */
    const TYPE_CREATE_SHOP = 3;

    /**
     * An user want to register an item to sell
     */
    const TYPE_USER_SELL_ITEM = 4;

    /**
     * A shop want to register an item to sell
     */
    const TYPE_SHOP_SELL_ITEM = 5;

    /**
     * An user want to be a shop' staff
     */
    const TYPE_SHOP_STAFF = 6;

    public static $type_value = [
        self::TYPE_REGISTER => 'Register',
        self::TYPE_CREATE_ITEM => 'Create Item',
        self::TYPE_CREATE_SHOP => 'Create Shop',
        self::TYPE_USER_SELL_ITEM => 'User Sell Item',
        self::TYPE_SHOP_SELL_ITEM => 'Shop Sell Item',
        self::TYPE_SHOP_STAFF => 'Shop Staff',
    ];
    /**
     *
     * @var integer
     */
    public $status;

    const STATUS_SENT = 1;
    const STATUS_ACCEPT = 2;
    const STATUS_REJECT = 3;
    const STATUS_CANCEL = 4;

    public static $status_value = [
        self::STATUS_SENT => 'SENT',
        self::STATUS_ACCEPT => 'ACCEPTED',
        self::STATUS_REJECT => 'REJECTED',
        self::STATUS_CANCEL => 'CANCELED',
    ];

    /**
     * Get Status value in string
     * @return int
     */
    public function getStatusValue()
    {
        if (isset(self::$status_value[$this->status])) {
            return self::$status_value[$this->status];
        }

        return $this->status;
    }

    /**
     * Get type value in string
     * @return int
     */
    public function getTypeValue()
    {
        if (isset(self::$type_value[$this->type])) {
            return self::$type_value[$this->type];
        }

        return $this->type;
    }

    public function printStatus()
    {
        $val = $this->getStatusValue();
        switch ($this->status) {
            case self::STATUS_SENT:
                return "<span class='label label-info'>$val</span>";
            case self::STATUS_ACCEPT:
                return "<span class='label label-success'>$val</span>";
            case self::STATUS_REJECT:
                return "<span class='label label-danger'>$val</span>";
            default:
                return "<span class='label label-warning'>$val</span>";
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'from_user_id' => 'from_user_id',
            'to_user_id' => 'to_user_id',
            'from_shop_id' => 'from_shop_id',
            'type' => 'type',
            'status' => 'status',
            'item_id' => 'item_id',
            'updated_by' => 'updated_by',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
    }

    /**
     * Before validation on create
     */
    public function beforeValidationOnCreate()
    {
        $this->status = self::STATUS_SENT;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('item_id', 'Items', 'id', ['alias' => 'item']);
        $this->belongsTo('from_shop_id', 'Shops', 'id', ['alias' => 'shop']);
        $this->belongsTo('from_user_id', 'Users', 'id', ['alias' => 'fromUser']);
        $this->belongsTo('to_user_id', 'Users', 'id', ['alias' => 'toUser']);
        $this->belongsTo('updated_by', 'Users', 'id', ['alias' => 'updatedBy']);
    }

    public function getDestination()
    {
        if ($this->to_user_id) {
            return $this->toUser->username;
        }

        return 'HKT Administrators';
    }

    public function isStatusSent()
    {
        return $this->status == self::STATUS_SENT;
    }

    /**
     * The request is canceled
     */
    public function beCanceled($user_id = null)
    {
        if (!$this->isStatusSent()) {
            return false;
        }
        if (!$user_id) {
            $user_id = $this->from_user_id;
        }
        $this->status = self::STATUS_CANCEL;
        $this->updated_by = $user_id;
        return $this->save();
    }

    /**
     * @param null|int $user_id
     * @return bool
     */
    public function beRejected($user_id = null)
    {
        if (!$this->isStatusSent()) {
            return false;
        }
        $this->status = self::STATUS_REJECT;
        $this->updated_by = $user_id;

        return $this->save();
    }

    /**
     * @param null|int $user_id
     * @return bool
     */
    public function beAccepted($user_id = null)
    {
        if (!$this->isStatusSent()) {
            return false;
        }

        $this->status = self::STATUS_ACCEPT;
        $this->updated_by = $user_id;

        switch ($this->type) {
            case self::TYPE_REGISTER:
                $this->fromUser->changeRole(Users::ROLE_USER);
                break;
            case self::TYPE_CREATE_ITEM:
                $this->item->changeStatus(Items::STATUS_AVAILABLE);
                break;
            case self::TYPE_CREATE_SHOP:
                $this->shop->changeStatus(Shops::STATUS_NORMAL);
                break;
            case self::TYPE_USER_SELL_ITEM:
                $item_user = new ItemUsers();
                $item_user->item_id = $this->item_id;
                $item_user->user_id = $this->from_user_id;
                $item_user->status = ItemUsers::STATUS_NORMAL;
                $item_user->save();
                break;
            case self::TYPE_SHOP_SELL_ITEM:
                $item_shop = new ItemShops();
                $item_shop->item_id = $this->item_id;
                $item_shop->shop_id = $this->from_shop_id;
                $item_shop->status = ItemShops::STATUS_NORMAL;
                $item_shop->save();
                break;
            case self::TYPE_SHOP_STAFF:
                break;
        }

        return $this->save();
    }
}
