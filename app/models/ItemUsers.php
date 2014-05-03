<?php




class ItemUsers extends BModel
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
    public $item_id;

    /**
     *
     * @var integer
     */
    public $user_id;

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
    const STATUS_NORMAL = 0;
    const STATUS_FORCE_SALE = 1;
    const STATUS_FORCE_NOT_SALE = 2;

    public static $status_value = [
        self::STATUS_NORMAL => 'NORMAL',
        self::STATUS_FORCE_SALE => 'FORCE SALE',
        self::STATUS_FORCE_NOT_SALE => 'FORCE NOT SALE',
    ];

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
            case self::STATUS_FORCE_SALE:
                return "<span class='label label-primary'>$val</span>";
            case self::STATUS_FORCE_NOT_SALE:
                return "<span class='label label-danger'>$val</span>";
            default:
                return "<span class='label label-info'>$val</span>";
        }
    }
    /**
     *
     * @var string
     */
    public $start_sale_date;

    /**
     *
     * @var string
     */
    public $end_sale_date;

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
            'item_id' => 'item_id',
            'user_id' => 'user_id',
            'price' => 'price',
            'status' => 'status',
            'start_sale_date' => 'start_sale_date',
            'end_sale_date' => 'end_sale_date',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at'
        );
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('user_id', 'Users', 'id', ['alias' => 'user']);
        $this->belongsTo('item_id', 'Items', 'id', ['alias' => 'item']);
    }

    public function isForceSale()
    {
        return $this->status == self::STATUS_FORCE_SALE;
    }

    public function isForceNotSale()
    {
        return $this->status == self::STATUS_FORCE_NOT_SALE;
    }

    public function isInSaleTime()
    {
        $time = date('Y-m-d H-i-s');
        $this->start_sale_date <= $time && $time <= $this->end_sale_date;
    }

    /**
     * Check where an item is on sale or not
     * @return bool
     */
    public function isOnSale()
    {
        return $this->isForceSale() || (!$this->isForceNotSale() && $this->isInSaleTime());
    }

    /**
     * @param int $type
     * @return \Phalcon\Mvc\Model\Resultset\Simple
     */
    public static function getOnSaleItems($type=Items::TYPE_NORMAL)
    {
        $time = date('Y-m-d H-i-s');
        $sql = "SELECT * FROM item_users, items WHERE (item_users.status = ? OR (item_users.status != ? AND (item_users.start_sale_date <= ? OR item_users.end_sale_date >= ?))) AND (item_users.item_id = items.id AND items.type = ?)";
        $params = [self::STATUS_FORCE_SALE, self::STATUS_FORCE_NOT_SALE, $time, $time, $type];
        $item_user = new ItemUsers();
        return new Phalcon\Mvc\Model\Resultset\Simple(null, $item_user, $item_user->getReadConnection()->query($sql, $params));
    }

    public function getItemJsonObject()
    {
        $obj = [
            'item_id' => $this->item_id,
            'item_user_id' => $this->id,
            'name' => $this->item->name,
            'img' => $this->item->getImageLink(),
            'seller' => $this->user->username,
            'price' => $this->item->getSalePrice($this->user_id),
        ];
        return json_encode($obj);
    }

    public function getSalePrice()
    {
        if ($this->price) {
            return $this->price;
        }
        return $this->item->price;
    }

    public function beForced($status=self::STATUS_NORMAL)
    {
        if($this->status != $status) {
            $this->status = $status;
            $this->save();
        }
    }

    public function printActionButtonGroup()
    {
        $normal_btn = " <button class='btn btn-primary btn-action item-user-change-status' data-item-user-id='{$this->id}' data-status='" . self::STATUS_NORMAL . "'>Force Normal</button> ";
        $sale_btn = " <button class='btn btn-warning btn-action item-user-change-status' data-item-user-id='{$this->id}' data-status='" . self::STATUS_FORCE_SALE . "'>Force Sale</button> ";
        $not_sale_btn = " <button class='btn btn-danger btn-action item-user-change-status' data-item-user-id='{$this->id}' data-status='" . self::STATUS_FORCE_NOT_SALE . "'>Force Not Sale</button> ";
        if($this->isForceSale()) {
            return $normal_btn . $not_sale_btn;
        } elseif ($this->isForceNotSale()) {
            return $normal_btn . $sale_btn;
        } else {
            return $sale_btn . $not_sale_btn;
        }
    }
}
