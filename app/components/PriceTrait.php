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
}

