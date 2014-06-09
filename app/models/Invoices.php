<?php


class Invoices extends BModel
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
    public $to_shop_id;

    /**
     *
     * @var integer
     */
    public $item_id;

    /**
     *
     * @var integer
     */
    public $item_type;

    /**
     *
     * @var integer
     */
    public $item_count;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $real_price;

    /**
     *
     * @var integer
     */
    public $hcoin_receive;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $set_items_id;

    /**
     *
     * @var string
     */
    public $comment;

    /**
     *
     * @var integer
     */
    public $updated_by;

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

    const STATUS_SENT = 1;
    const STATUS_ACCEPT = 2;
    const STATUS_REJECT = 3;
    const STATUS_CANCEL = 4;

    public static $status_value = [
        self::STATUS_SENT => 'SENT',
        self::STATUS_ACCEPT => 'ACCEPTED',
        self::STATUS_REJECT => 'REJECTED',
        self::STATUS_CANCEL => 'CANCELED',
    ];

    const TYPE_SENT = 1;
    const TYPE_RECEIVED = 2;

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
            case self::STATUS_SENT:
                return "<span class='label label-info'>$val</span>";
            case self::STATUS_ACCEPT:
                return "<span class='label label-success'>$val</span>";
            case self::STATUS_REJECT:
                return "<span class='label label-danger'>$val</span>";
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
            'to_shop_id' => 'to_shop_id',
            'item_id' => 'item_id',
            'item_type' => 'item_type',
            'item_count' => 'item_count',
            'price' => 'price',
            'real_price' => 'real_price',
            'hcoin_receive' => 'hcoin_receive',
            'status' => 'status',
            'set_items_id' => 'set_items_id',
            'comment' => 'comment',
            'updated_by' => 'updated_by',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at'
        ];
    }

    public function beforeValidationOnCreate()
    {
        $this->status = self::STATUS_SENT;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo('item_id', 'Items', 'id', ['alias' => 'item']);
        $this->belongsTo('from_user_id', 'Users', 'id', ['alias' => 'fromUser']);
        $this->belongsTo('updated_by', 'Users', 'id', ['alias' => 'updatedBy']);
        $this->belongsTo('to_user_id', 'Users', 'id', ['alias' => 'toUser']);
        $this->belongsTo('to_shop_id', 'Shops', 'id', ['alias' => 'toShop']);
    }

    /**
     * Check whether the status is STATUS_SENT or not
     * @return bool
     */
    public function isStatusSent()
    {
        return $this->status == self::STATUS_SENT;
    }

    /**
     * The invoice is canceled
     * @var int $user_id
     * @return bool
     */
    public function beCanceled($user_id = 0)
    {
        if (!$this->isStatusSent()) {
            return false;
        }

        if ($this->item->isNormalItem()) {
            if ($this->price) {
                $wallet_before = $this->fromUser->wallet;
                $wallet_after = $this->fromUser->increaseWallet($this->price);
                WalletLogs::createNew($this->from_user_id, $wallet_before, $wallet_after, $this->id, WalletLogs::ACTION_CANCEL);
            }
        }
        $this->status = self::STATUS_CANCEL;
        $this->updated_by = $user_id;
        return $this->save();
    }

    /**
     * The invoice is accepted
     * @var int $user_id
     * @return bool
     */
    public function beAccepted($user_id = 0)
    {
        if (!$this->isStatusSent()) {
            return false;
        }

        if ($this->item->isNormalItem()) {
            if ($this->real_price) {
                if ($this->to_user_id) {
                    $to_user = $this->toUser;
                } else {
                    $to_user = $this->toShop->user;
                }

                $wallet_before = $to_user->wallet;
                $wallet_after = $to_user->increaseWallet($this->real_price);
                WalletLogs::createNew($to_user->id, $wallet_before, $wallet_after, $this->id, WalletLogs::ACTION_ACCEPT);
                if ($this->to_shop_id) {
                    $this->toShop->increaseSale($this->real_price);
                }
            }

            if ($this->hcoin_receive) {
                $hcoin_before = $this->fromUser->hcoin;
                $hcoin_after = $this->fromUser->increaseHCoin($this->hcoin_receive);
                WalletLogs::createNew($this->from_user_id, $hcoin_before, $hcoin_after, $this->id, WalletLogs::ACTION_ACCEPT, WalletLogs::TYPE_HCOIN);
            }
        } elseif ($this->item->isDepositItem()) {
            $wallet_before = $this->fromUser->wallet;
            $wallet_after = $this->fromUser->increaseWallet($this->real_price);
            WalletLogs::createNew($this->from_user_id, $wallet_before, $wallet_after, $this->id, WalletLogs::ACTION_ACCEPT);
        } elseif ($this->item->isWithdrawItem()) {
            $wallet_before = $this->fromUser->wallet;
            $wallet_after = $this->fromUser->minusWallet($this->real_price);
            if ($wallet_after === false) {
                return false;
            }
            WalletLogs::createNew($this->from_user_id, $wallet_before, $wallet_after, $this->id, WalletLogs::ACTION_ACCEPT);
        }
        $this->status = self::STATUS_ACCEPT;
        $this->updated_by = $user_id;

        $this->save();

        return true;
    }

    /**
     * The invoice is rejected
     * @var int $user_id
     * @return bool
     */
    public function beRejected($user_id = 0)
    {
        if (!$this->isStatusSent()) {
            return false;
        }

        if ($this->item->isNormalItem()) {
            if ($this->price) {
                $wallet_before = $this->fromUser->wallet;
                $wallet_after = $this->fromUser->increaseWallet($this->price);
                WalletLogs::createNew($this->from_user_id, $wallet_before, $wallet_after, $this->id, WalletLogs::ACTION_REJECT);
            }
        }
        $this->status = self::STATUS_REJECT;
        $this->updated_by = $user_id;
        $this->save();

        return true;
    }

    /**
     * @param int $status
     * @param Users $user
     * @return string $error
     */
    public function changeStatus($status, $user)
    {
        $error = '';
        switch ($status) {
            case Invoices::STATUS_ACCEPT:
                if ($user->canAcceptInvoice($this)) {
                    if (!$this->beAccepted($user->id)) {
                        $error = 'User do not have enough money! Invoice can not be accepted';
                    }
                } else {
                    $error = 'Authorized Fail';
                }
                break;
            case Invoices::STATUS_REJECT:
                if ($user->canAcceptInvoice($this)) {
                    $this->beRejected($user->id);
                } else {
                    $error = 'Authorized Fail';
                }
                break;
            case Invoices::STATUS_CANCEL:
                if ($user->canCancelInvoice($this)) {
                    $this->beCanceled($user->id);
                } else {
                    $error = 'Authorized Fail';
                }
                break;
        }
        return $error;
    }

    /**
     * Get set of an invoice
     * @return ItemShops[]
     */
    public function getSetItems()
    {
        $result = [];
        if ($this->set_items_id) {
            $item_shop_ids = json_decode($this->set_items_id);
            if (is_array($item_shop_ids)) {
                foreach ($item_shop_ids as $item_shop_id) {
                    $item_shop = ItemShops::findFirstById($item_shop_id);
                    if ($item_shop) {
                        $result[] = $item_shop;
                    }
                }
            }
        }
        return $result;
    }
}
