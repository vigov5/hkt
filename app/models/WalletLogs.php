<?php


class WalletLogs extends BModel
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
    public $before;

    /**
     *
     * @var integer
     */
    public $after;

    /**
     *
     * @var integer
     */
    public $invoice_id;

    /**
     *
     * @var integer
     */
    public $transaction_id;

    /**
     *
     * @var integer
     */
    public $action;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var string
     */
    public $created_at;

    const ACTION_CANCEL = 0;
    const ACTION_CREATE = 1;
    const ACTION_ACCEPT = 2;
    const ACTION_REJECT = 3;
    const ACTION_REFUND = 4;

    const ACTION_SENT = 5;
    const ACTION_RECEIVED = 6;


    public static $action_value = [
        self::ACTION_CANCEL => 'CANCELED',
        self::ACTION_CREATE => 'CREATED',
        self::ACTION_ACCEPT => 'ACCEPTED',
        self::ACTION_REJECT => 'REJECTED',
        self::ACTION_REFUND => 'REFUND',
        self::ACTION_SENT => 'SENT',
        self::ACTION_RECEIVED => 'RECEIVED',
    ];

    public function getActionValue()
    {
        if (isset(self::$action_value[$this->action])) {
            return self::$action_value[$this->action];
        }

        return $this->action;
    }

    public function printAction()
    {
        $val = $this->getActionValue();
        switch ($this->action) {
            case self::ACTION_CREATE:
            case self::ACTION_SENT:
                return "<span class='label label-primary'>$val</span>";
            case self::ACTION_REFUND:
                return "<span class='label label-info'>$val</span>";
            case self::ACTION_ACCEPT:
            case self::ACTION_RECEIVED:
                return "<span class='label label-success'>$val</span>";
            case self::ACTION_REJECT:
                return "<span class='label label-danger'>$val</span>";
            default:
                return "<span class='label label-warning'>$val</span>";
        }
    }

    const TYPE_MONEY = 0;
    const TYPE_HCOIN = 1;
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'before' => 'before',
            'after' => 'after',
            'action' => 'action',
            'type' => 'type',
            'invoice_id' => 'invoice_id',
            'transaction_id' => 'transaction_id',
            'created_at' => 'created_at'
        );
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('user_id', 'Users', 'id', ['alias' => 'user']);
        $this->belongsTo('invoice_id', 'Invoices', 'id', ['alias' => 'invoice']);
        $this->belongsTo('transaction_id', 'CoinDonations', 'id', ['alias' => 'donation']);
        $this->belongsTo('transaction_id', 'MoneyTransfers', 'id', ['alias' => 'transfer']);
    }

    /**
     * @param int $user_id
     * @param int $before
     * @param int $after
     * @param int $invoice_id
     * @param int $transaction_id
     * @param int $action
     * @param int $type
     */
    public static function createNew($user_id, $before, $after, $invoice_id, $transaction_id, $action = self::ACTION_ACCEPT, $type = self::TYPE_MONEY)
    {
        $hcoin_log = new WalletLogs();
        $hcoin_log->create([
            'user_id' => $user_id,
            'before' => $before,
            'after' => $after,
            'action' => $action,
            'invoice_id' => $invoice_id,
            'transaction_id' => $transaction_id,
            'type' => $type,
        ]);
    }
}
