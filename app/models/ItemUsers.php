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
        $this->belongsTo('item_id', 'Users', 'id', ['alias' => 'item']);
    }

    public function isOnSale()
    {
        $time = date('Y-m-d H-i-s');
        return $this->force_sale || ($this->start_sale_date <= $time && $time <= $this->end_sale_date);
    }
}
