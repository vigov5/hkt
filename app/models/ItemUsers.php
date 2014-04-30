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
    public $force_sale;
    const FORCE_SALE = 1;
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
            'force_sale' => 'force_sale',
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

    /**
     * Check where an item is on sale or not
     * @return bool
     */
    public function isOnSale()
    {
        $time = date('Y-m-d H-i-s');
        return $this->force_sale || ($this->start_sale_date <= $time && $time <= $this->end_sale_date);
    }

    /**
     * @param int $type
     * @return \Phalcon\Mvc\Model\Resultset\Simple
     */
    public static function getOnSaleItems($type=Items::TYPE_NORMAL)
    {
        $time = date('Y-m-d H-i-s');
        $sql = "SELECT * FROM item_users, items WHERE (item_users.force_sale != 0 OR (item_users.start_sale_date <= ? OR item_users.end_sale_date >= ?)) AND (item_users.item_id = items.id AND items.type = ?)";
        $params = [$time, $time, $type];
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
}
