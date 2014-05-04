<?php


class ItemShops extends \Phalcon\Mvc\Model
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
    public $force_sale;

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
            'force_sale' => 'force_sale',
            'start_sale_date' => 'start_sale_date',
            'end_sale_date' => 'end_sale_date',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at'
        );
    }

}
