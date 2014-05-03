<?php




class Invoices extends BModel
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
    public $to_shop_id;

    /**
     *
     * @var integer
     */
    public $item_id;

    /**
     *
     * @var integer
     */
    public $item_count;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $set_items_id;

    /**
     *
     * @var string
     */
    public $comment;

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

    public function getStatusValue()
    {
        if (isset(self::$status_value[$this->status])) {
            return self::$status_value[$this->status];
        }
        return $this->status;
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
        return array(
            'id' => 'id',
            'from_user_id' => 'from_user_id',
            'to_user_id' => 'to_user_id',
            'to_shop_id' => 'to_shop_id',
            'item_id' => 'item_id',
            'item_count' => 'item_count',
            'price' => 'price',
            'status' => 'status',
            'set_items_id' => 'set_items_id',
            'comment' => 'comment',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        );
    }

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
    }

    /**
     * Check whether the status is STATUS_SENT or not
     * @return bool
     */
    public function isStatusSent()
    {
        return $this->status == self::STATUS_SENT;
    }

    /**
     * The invoice is canceled
     */
    public function beCanceled()
    {
        if (!$this->isStatusSent()) {
            return false;
        }
        $this->status = self::STATUS_CANCEL;
        $this->fromUser->increaseWallet($this->price);
        return $this->save();
    }

    /**
     * The invoice is accepted
     */
    public function beAccepted()
    {
        if (!$this->isStatusSent()) {
            return false;
        }
        $this->status = self::STATUS_ACCEPT;
        $this->toUser->increaseWallet($this->price);
        $this->save();
        return true;
    }

    /**
     * The invoice is rejected
     */
    public function beRejected()
    {
        if (!$this->isStatusSent()) {
            return false;
        }
        $this->status = self::STATUS_REJECT;
        $this->fromUser->increaseWallet($this->price);
        $this->save();
        return true;
    }
}
