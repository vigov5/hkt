<?php




class GameKpis extends BModel
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
    public $day;

    /**
     *
     * @var integer
     */
    public $total_users;

    /**
     *
     * @var integer
     */
    public $new_users;

    /**
     *
     * @var integer
     */
    public $login_users;

    /**
     *
     * @var integer
     */
    public $deposit_users;

    /**
     *
     * @var integer
     */
    public $withdraw_users;

    /**
     *
     * @var integer
     */
    public $purchase_users;

    /**
     *
     * @var integer
     */
    public $deposit;

    /**
     *
     * @var integer
     */
    public $withdraw;

    /**
     *
     * @var integer
     */
    public $purchase;

    /**
     *
     * @var integer
     */
    public $hcoin;

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
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'day' => 'day',
            'total_users' => 'total_users',
            'new_users' => 'new_users',
            'login_users' => 'login_users',
            'deposit_users' => 'deposit_users',
            'withdraw_users' => 'withdraw_users',
            'purchase_users' => 'purchase_users',
            'deposit' => 'deposit',
            'withdraw' => 'withdraw',
            'purchase' => 'purchase',
            'hcoin' => 'hcoin',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        );
    }

}
