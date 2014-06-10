<?php
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Items extends BModel
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
    public $name;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $img;

    /**
     *
     * @var integer
     */
    public $public_range;

    /**
     *
     * @var integer
     */
    public $created_by;

    /**
     *
     * @var integer
     */
    public $approved_by;

    /**
     *
     * @var string
     */
    public $approved_at;

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
     * Status available and unavailable
     */
    const STATUS_UNAVAILABLE = 0;
    const STATUS_AVAILABLE = 1;

    /**
     *
     * @var array $item_status
     */
    public static $item_status = [
        self::STATUS_UNAVAILABLE => 'Unavailable',
        self::STATUS_AVAILABLE => 'Available'
    ];

    /**
     * Get item status in string. If it does not exist in list item types, then return the status itself.
     * Can be accessed via magic method by using $this->status_value
     * @return str|int
     */
    public function getStatusValue()
    {
        if (isset(self::$item_status[$this->status])) {
            return self::$item_status[$this->status];
        }

        return $this->status;
    }

    /**
     * Item type
     */
    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAW = 2;
    const TYPE_NORMAL = 3;
    const TYPE_SET = 4;

    /**
     * @var array $item_type
     */
    public static $item_types = [
        self::TYPE_NORMAL => 'Normal',
        self::TYPE_SET => 'Set',
        self::TYPE_DEPOSIT => 'Deposit',
        self::TYPE_WITHDRAW => 'Withdraw',
    ];

    public static $item_types_restricted = [
        self::TYPE_NORMAL => 'Normal',
        self::TYPE_SET => 'Set',
    ];

    use ImageTrait;

    /**
     * Get item type in string. If it does not exist in list item types, then return the type itself
     * Can be accessed via magic method by using $this->type_value
     * @return str|int
     */
    public function getTypeValue()
    {
        if (isset(self::$item_types[$this->type])) {
            return self::$item_types[$this->type];
        }

        return $this->type;
    }

    /**
     * @return array Save Attributes
     */
    public function getSaveAttributesName()
    {
        return ['name', 'price', 'type', 'description', 'img', 'public_range'];
    }

    /**
     * @return array Attribute Labels
     */
    public static function getAttributeLabels()
    {
        return [
            'img' => 'Image Path',
        ];
    }

    public function beforeCreate()
    {
        $this->status = self::STATUS_UNAVAILABLE;
    }

    /**
     * @return bool
     */
    public function validation()
    {
        $this->validate(new Uniqueness(['field' => 'name']));
        $this->validate(new ImageValidator(['field' => 'img']));
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
        $this->belongsTo('created_by', 'Users', 'id', ['alias' => 'user']);
        $this->hasMany('id', 'Invoices', 'item_id');
        $this->hasMany('id', 'Requests', 'item_id');
        $this->hasMany('id', 'ItemUsers', 'item_id');
        $this->hasManyToMany('id', 'ItemUsers', 'item_id', 'user_id', 'Users', 'id', ['alias' => 'saleUsers']);
        $this->hasManyToMany('id', 'ItemShops', 'item_id', 'shop_id', 'Shops', 'id', ['alias' => 'saleShops']);
        $this->hasMany('id', 'ItemShops', 'item_id');
    }

    /**
     * @return bool Item is available or not
     */
    public function isAvailable()
    {
        return $this->status == self::STATUS_AVAILABLE;
    }

    /**
     * Change current item status
     * @param int $status
     */
    public function changeStatus($status)
    {
        if ($this->status != $status) {
            $this->status = $status;
            $this->save();
        }
    }

    /**
     * Check whether this item is NORMAL ITEM or not
     * @return bool
     */
    public function isNormalItem()
    {
        return $this->type == self::TYPE_NORMAL;
    }

    /**
     * Check whether this item is DEPOSIT ITEM or not
     * @return bool
     */
    public function isDepositItem()
    {
        return $this->type == self::TYPE_DEPOSIT;
    }

    /**
     * Check whether this item is WITHDRAW ITEM or not
     * @return bool
     */
    public function isWithdrawItem()
    {
        return $this->type == self::TYPE_WITHDRAW;
    }

    /**
     * Check whether this item is SET ITEM or not
     * @return bool
     */
    public function isSetItem()
    {
        return $this->type == self::TYPE_SET;
    }
    /**
     * Check if an item is on sale or not
     * @return bool
     */
    public function isOnSale()
    {
        if (!$this->isAvailable()) {
            return false;
        }
        foreach ($this->itemusers as $itemuser) {
            if ($itemuser->isOnSale()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all items that are sale by users
     * @return mixed
     */
    public static function getOnSaleItems()
    {
        $type = self::TYPE_NORMAL;

        return self::find([
                "type = $type",
            ]
        )->filter(function ($item) {
                    if ($item->isOnSale()) {
                        return $item;
                    }
                }
            );
    }

    /**
     * @param int|null $user_id
     * @return int price
     */
    public function getSalePrice($user_id = null)
    {
        if (!$user_id) {
            return $this->price;
        }
        $item_users = $this->getItemUsers(["user_id = $user_id"]);
        if (!$item_users) {
            return $this->price;
        }
        if ($item_users[0]->price) {
            return $item_users[0]->price;
        }

        return $this->price;
    }

    /**
     * @param string $text
     * @return string Return View Link Button
     */
    public function createViewLink($text = 'View')
    {
        return '<a href="/item/view/' . $this->id . '" class="btn btn-info btn-sm btn-action" role="button">' . $text . '</a>';
    }

    /**
     * @return string Item view link
     */
    public function getViewLink()
    {
        return '<a class="no-underline" href="' . "/item/view/{$this->id}" . '">' . $this->name . '</a>';
    }
}
