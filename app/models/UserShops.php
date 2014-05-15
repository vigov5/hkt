<?php




class UserShops extends BModel
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $shop_id;

    /**
     *
     * @var string
     */
    public $created_at;

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
            'user_id' => 'user_id',
            'shop_id' => 'shop_id',
            'created_at' => 'created_at',
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
        $this->belongsTo('item_id', 'Shops', 'id', ['alias' => 'shop']);
    }

    /**
     * Create new record
     * @param int $user_id
     * @param int $shop_id
     * @return bool
     */
    public static function createNew($user_id, $shop_id)
    {
        $user_shop = new UserShops();
        $user_shop->user_id = $user_id;
        $user_shop->shop_id = $shop_id;
        return $user_shop->save();
    }
}
