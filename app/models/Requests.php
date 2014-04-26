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

    /**
     *
     * @var integer
     */
    public $status;

    const STATUS_SENT = 1;
    const STATUS_ACCEPT = 2;
    const STATUS_REJECT = 3;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'from_user_id' => 'from_user_id',
            'to_user_id' => 'to_user_id',
            'from_shop_id' => 'from_shop_id',
            'type' => 'type',
            'status' => 'status'
        );
    }

    public function beforeValidationOnCreate()
    {
        $this->status = self::STATUS_SENT;
    }
}
