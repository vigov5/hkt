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

    public static function getAllOpenShops()
    {
        $time = date('Y-m-d H:i:s');
        $shops = Shops::find([
            'conditions' => 'status =' . self::STATUS_OPEN . ' OR (status =' . self::STATUS_NORMAL . ' AND start_date <= :time: AND :time: <= end_date)',
            'bind' => ['time' => $time],
        ]);
        return $shops;
    }
}
