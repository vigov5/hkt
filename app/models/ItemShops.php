<?php


class ItemShops extends BModel
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
    public $shop_id;

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
            'shop_id' => 'shop_id',
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
     * @return array Save Attributes
     */
    public function getSaveAttributesName()
    {
        return ['price', 'status', 'start_sale_date', 'end_sale_date'];
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('item_id', 'Items', 'id', ['alias' => 'item']);
        $this->belongsTo('shop_id', 'Shops', 'id', ['alias' => 'shop']);
    }

    /**
     * Validations and business logic
     */
    public function validation()
    {

        $this->validate(new \Phalcon\Mvc\Model\Validator\Numericality(['field' => 'price']));

        if ($this->validationHasFailed() == true) {
            return false;
        }
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
        $time = date('Y-m-d H:i:s');

        return ($this->start_sale_date <= $time && $time <= $this->end_sale_date);
    }

    /**
     * Check where an item is on sale or not
     * @return bool
     */
    public function isOnSale()
    {
        return $this->isForceSale() || (!$this->isForceNotSale() && $this->isInSaleTime());
    }

    public function printActionButtonGroup()
    {
        $normal_btn =
            " <button class='btn btn-primary btn-action item-shop-change-status' data-item-shop-id='{$this->id}' data-status='" .
            self::STATUS_NORMAL . "'>Force Normal</button> ";
        $sale_btn =
            " <button class='btn btn-warning btn-action item-shop-change-status' data-item-shop-id='{$this->id}' data-status='" .
            self::STATUS_FORCE_SALE . "'>Force Sale</button> ";
        $not_sale_btn =
            " <button class='btn btn-danger btn-action item-shop-change-status' data-item-shop-id='{$this->id}' data-status='" .
            self::STATUS_FORCE_NOT_SALE . "'>Force Not Sale</button> ";
        if ($this->isForceSale()) {
            return $normal_btn . $not_sale_btn;
        } elseif ($this->isForceNotSale()) {
            return $normal_btn . $sale_btn;
        } else {
            return $sale_btn . $not_sale_btn;
        }
    }

    public function getSalePrice()
    {
        if ($this->item->isSetItem()) {
            return 0;
        }
        if ($this->price) {
            return $this->price;
        }

        return $this->item->price;
    }

    public function beForced($status = self::STATUS_NORMAL)
    {
        if ($this->status != $status) {
            $this->status = $status;
            $this->save();
        }
    }

    public static function createNew($item_id, $shop_id, $price = 0, $status = self::STATUS_NORMAL)
    {
        $item_shop = new ItemShops();
        $item_shop->item_id = $item_id;
        $item_shop->shop_id = $shop_id;
        $item_shop->price = $price;
        $item_shop->status = $status;
        $item_shop->save();
    }

    public function getItemJsonObject()
    {
        $obj = [
            'item_id' => $this->item_id,
            'item_shop_id' => $this->id,
            'name' => $this->item->name,
            'img' => $this->item->getImageLink(),
            'seller' => $this->shop->name,
            'price' => $this->getSalePrice(),
        ];

        return json_encode($obj);
    }
}
