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
     * An user want to buy an item
     */
    const TYPE_BUY_ITEM = 4;

    /**
     * An user want to register an item to sell
     */
    const TYPE_USER_SELL_ITEM = 5;

    /**
     * A shop want to register an item to sell
     */
    const TYPE_SHOP_SELL_ITEM = 6;

    public static $type_value = [
        self::TYPE_REGISTER => 'Register',
        self::TYPE_CREATE_ITEM => 'Create Item',
        self::TYPE_CREATE_SHOP => 'Create Shop',
        self::TYPE_BUY_ITEM => 'Buy Item',
        self::TYPE_USER_SELL_ITEM => 'User Sell Item',
        self::TYPE_SHOP_SELL_ITEM => 'Shop Sell Item',
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

    const TYPE_SENT = 1;
    const TYPE_RECEIVED = 2;

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
}
