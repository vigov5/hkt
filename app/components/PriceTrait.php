<?php
/**
 * @author Tran Duc Thang
 * @date 5/17/14
 *
 */

trait PriceTrait {

    /**
     * Get the HCoin that user will receive if buy this item
     * @return int
     */
    public function getHCoinReceived()
    {
        if ($this->item->isNormalItem()) {
            $rate = Setting::getHCoinRate();
            return $this->getSalePrice() * $rate / 100;
        }
        return 0;
    }

    /**
     * Get the real price of this item.
     * If this item is a Normal Item, it will be charge a fr
     * @return float|int
     */
    public function getRealPrice()
    {
        if ($this->item->isNormalItem()) {
            $rate = Setting::getChargeRate();
            return floor($this->getSalePrice() * (100 - $rate) / 100);
        }
        return $this->getSalePrice();
    }

    /**
     * Get the real charged amount of this transfer
     * If sender hander the fee, the total amount will include the fee beside the transfer amount
     * @param int $amount
     * @param int $fee_bearer
     * @return float|int
     */
    public function getChargedAmount($amount, $fee_bearer)
    {
        if ($fee_bearer == MoneyTransfers::SENDER_FEE) {
            $rate = Setting::getTransferRate();
            return floor($amount * (100 + $rate) / 100);
        }
        return $amount;
    }

    /**
     * Get the real amount will be transfered
     * If recipient hander the fee, the real amount will be substracted the fee
     * @param int $amount
     * @param int $fee_bearer
     * @return float|int
     */
    public function getTransferedAmount($amount, $fee_bearer)
    {
        if ($fee_bearer == MoneyTransfers::RECIPIENT_FEE) {
            $rate = Setting::getTransferRate();
            return floor($amount * (100 - $rate) / 100);
        }
        return $amount;
    }
}

