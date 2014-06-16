<?php


class CoinDonations extends BModel
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
    public $hcoin_amount;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'from_user_id' => 'from_user_id',
            'to_user_id' => 'to_user_id',
            'hcoin_amount' => 'hcoin_amount',
            'created_at' => 'created_at',
        ];
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('from_user_id', 'Users', 'id', ['alias' => 'fromUser']);
        $this->belongsTo('to_user_id', 'Users', 'id', ['alias' => 'toUser']);
    }
}
