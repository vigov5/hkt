<?php


class MoneyTransfers extends BModel
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
    public $transfer_amount;

    /**
     *
     * @var integer
     */
    public $charged_amount;


    /**
     *
     * @var integer
     */
    public $fee_bearer;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var integer
     */
    public $nonce;

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

    const STATUS_CREATE = 1;
    const STATUS_TRANSFER = 2;
    const STATUS_CANCEL = 3;
    const STATUS_EXPIRE = 4;

    const EXPIRE_TIME = 300; // 5 minutes

    public static $status_value = [
        self::STATUS_CREATE => 'CREATED',
        self::STATUS_TRANSFER => 'TRANSFERRED',
        self::STATUS_CANCEL => 'CANCELED',
        self::STATUS_EXPIRE => 'EXPIRED',
    ];

    const SENDER_FEE = 1;
    const RECIPIENT_FEE = 2;

    use PriceTrait;

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
            case self::STATUS_CREATE:
                return "<span class='label label-info'>$val</span>";
            case self::STATUS_TRANSFER:
                return "<span class='label label-success'>$val</span>";
            case self::STATUS_EXPIRE:
                return "<span class='label label-danger'>$val</span>";
            case self::STATUS_CANCEL:
                return "<span class='label label-warning'>$val</span>";
            default:
                return "<span class='label label-warning'>$val</span>";
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'from_user_id' => 'from_user_id',
            'to_user_id' => 'to_user_id',
            'transfer_amount' => 'transfer_amount',
            'charged_amount' => 'charged_amount',
            'fee_bearer' => 'fee_bearer',
            'status' => 'status',
            'nonce' => 'nonce',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
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

    public function isProcessing(){
        return $this->status == self::STATUS_CREATE;
    }

    public function isTransferExpired(){
        return (strtotime('now') - strtotime($this->created_at) > self::EXPIRE_TIME);
    }

    public function getEncodeData($nonce){
        $data = [
            (int) $this->id,
            (int) $this->from_user_id,
            (int) $this->to_user_id,
            $nonce,
            (int) $this->transfer_amount,
            (int) $this->charged_amount,
            $nonce,
            (int) $this->status,
            (int) $this->fee_bearer,
            $nonce,
            $this->created_at
        ];
        return serialize($data);
    }

    public function isValidConfirmation($auth){
        return $auth === CryptoHelper::calculateHMAC($this->nonce, $this->getEncodeData($this->nonce));
    }

    public function updateStatus(){
        if ($this->status == self::STATUS_CREATE && $this->isTransferExpired()) {
            $this->status = MoneyTransfers::STATUS_EXPIRE;
            $this->save();
        }
    }
}
