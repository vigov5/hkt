<?php
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Item extends BModel
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

    /**
     * @var array $item_type
     */
    public static $item_types = [
        self::TYPE_DEPOSIT => 'Deposit',
        self::TYPE_WITHDRAW => 'Withdraw',
        self::TYPE_NORMAL => 'Normal',
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

    public function getSaveAttributesName()
    {
        return ['name', 'price', 'type', 'description', 'img', 'public_range'];
    }

    public static function getAttributeLabels()
    {
        return [
            'img' => 'Image Path',
        ];
    }

    public function validation()
    {
        $this->validate(new Uniqueness(['field' => 'name']));
        $this->validate(new ImageValidator(['field' => 'img']));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}
