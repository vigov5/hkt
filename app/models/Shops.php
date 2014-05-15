<?php




class Shops extends BModel
{
    use ImageTrait;
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
    public $sales;

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
     * @var string
     */
    public $start_date;

    /**
     *
     * @var string
     */
    public $end_date;

    /**
     *
     * @var integer
     */
    public $created_by;

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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'name' => 'name',
            'status' => 'status',
            'sales' => 'sales',
            'description' => 'description',
            'img' => 'img',
            'start_date' => 'start_date',
            'end_date' => 'end_date',
            'created_by' => 'created_by',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at'
        );
    }

    /**
     * Status available and unavailable
     */
    const STATUS_UNAUTHORIZED = 0;
    const STATUS_OPEN = 1;
    const STATUS_NORMAL = 2;
    const STATUS_CLOSE = 3;

    /**
     *
     * @var array $shop_status
     */
    public static $shop_status = [
        self::STATUS_UNAUTHORIZED => 'Unauthorized',
        self::STATUS_OPEN => 'Open',
        self::STATUS_NORMAL => 'Normal',
        self::STATUS_CLOSE => 'Close',
    ];

    /**
     * Get shop status in string. If it does not exist in list shop types, then return the status itself.
     * Can be accessed via magic method by using $this->status_value
     * @return str|int
     */
    public function getStatusValue()
    {
        if (isset(self::$shop_status[$this->status])) {
            return self::$shop_status[$this->status];
        }

        return $this->status;
    }

    /**
     * Check a status is valid or not
     * @param int $status
     * @return bool
     */
    public static function isValidStatus($status)
    {
        return isset(self::$shop_status[$status]);
    }

    public function isUnauthorized()
    {
        return $this->status == self::STATUS_UNAUTHORIZED;
    }

    /**
     * @return array Save Attributes
     */
    public function getSaveAttributesName()
    {
        return ['name', 'description', 'img', 'status', 'start_date', 'end_date'];
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
        $this->status = self::STATUS_UNAUTHORIZED;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('created_by', 'Users', 'id', ['alias' => 'user']);
        $this->hasMany('id', 'ItemShops', 'shop_id');
        $this->hasMany('id', 'UserShops', 'shop_id');
        $this->hasManyToMany('id', 'ItemShops', 'shop_id', 'item_id', 'Items', 'id', ['alias' => 'sellItems']);
        $this->hasManyToMany('id', 'UserShops', 'shop_id', 'user_id', 'Users', 'id', ['alias' => 'shopStaffs']);
    }

    /**
     * @return bool
     */
    public function validation()
    {
        $this->validate(new \Phalcon\Mvc\Model\Validator\Uniqueness(['field' => 'name']));
        $this->validate(new ImageValidator(['field' => 'img']));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Change current shop status
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
     * @param Users $user
     * @return bool
     */
    public function checkOwner($user)
    {
        return $this->created_by == $user->id;
    }

    /**
     * @param Users $user
     */
    public function checkStaff($user)
    {
        $staff = $this->getUserShops(["user_id = {$user->id}"]);
        return $staff->count();
    }

    /**
     * Check whether the user is shop owner or a staff
     * @param Users $user
     * @return bool
     */
    public function checkOwnerOrStaff($user)
    {
        return $this->checkOwner($user) || $this->checkStaff($user);
    }

    /**
     * Get all open shops
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function getAllOpenShops()
    {
        $time = date('Y-m-d H:i:s');
        $shops = Shops::find([
            'conditions' => 'status =' . self::STATUS_OPEN . ' OR (status =' . self::STATUS_NORMAL . ' AND start_date <= :time: AND :time: <= end_date)',
            'bind' => ['time' => $time],
        ]);
        return $shops;
    }

    public function isOnOpen()
    {
        if ($this->status == self::STATUS_OPEN) {
            return true;
        }
        if ($this->status == self::STATUS_NORMAL) {
            $time = date('Y-m-d H:i:s');
            return $this->start_date <= $time && $time <= $this->end_date;
        }
        return false;
    }

    public function getAllOnSaleItems($type = null)
    {
        if (!$this->isOnOpen()) {
            return [];
        }

        $item_shops = $this->getItemShops()->filter(function ($item_shop) use ($type) {
            $valid = true;
            if (!$item_shop->isOnSale()) {
                $valid = false;
            }
            if ($valid && $type && $item_shop->item->type != $type) {
                $valid = false;
            };
            if ($valid) {
                return $item_shop;
            }
        });

        return $item_shops;
    }

    /**
     * @param Items $item
     * @return Requests
     */
    public function createNewItemRequest($item)
    {
        $request = new Requests();
        $request->item_id = $item->id;
        $request->from_user_id = $this->created_by;
        $request->to_user_id = 0;
        $request->from_shop_id = $this->id;
        $request->status = Requests::STATUS_SENT;
        $request->type = Requests::TYPE_CREATE_ITEM;
        $request->save();
        return $request;
    }
}
