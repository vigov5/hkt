<?php
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

    const STATUS_UNAVAILABLE = 0;
    const STATUS_AVAILABLE = 1;

    /**
    * 
    * @var array $item_status 
    */
    public static $item_status = [self::STATUS_UNAVAILABLE => 'Unavailable', self::STATUS_AVAILABLE => 'Available'];

    const TYPE_DEPOSIT = 1;
    const TYPE_WITHDRAW = 2;
    const TYPE_NORMAL = 3;

    public static $item_types = [
        self::TYPE_DEPOSIT => 'Deposit',
        self::TYPE_WITHDRAW => 'Withdraw',
        self::TYPE_NORMAL => 'Normal',        
    ];

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
}
