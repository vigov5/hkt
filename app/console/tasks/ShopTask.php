<?php

class ShopTask extends \Phalcon\CLI\Task
{
    public function openAction($shop_id)
    {
        $datetime = DateHelper::now();
        $shop = Shops::findFirst($shop_id);
        if ($shop) {
            $favorites = Favorites::getSubscribers($shop);
            $emails = [];
            foreach ($favorites as $favorite) {
                $emails[] = $favorite->user->email;
            }
            $mail = new Mail();
            $mail->send($emails, "[HKT] $shop->name Notification", 'shop_open', ['shop' => $shop, 'datetime' => $datetime]);
        }
    }
}
