<?php
/**
 * @author Tran Duc Thang
 *
 */

class Console
{
    public static function run($task, $action, $param)
    {
        $cli_file = realpath(dirname(__FILE__) . "/../") . '/console/cli.php';
        exec("php $cli_file $task $action $param > /dev/null 2>&1 &");
    }

    public static function sendShopOpenNotification($shop_id)
    {
        self::run('shop', 'open', $shop_id);
    }

    public static function sendDonationReceivedNotification($data)
    {
        $encoded_data = base64_encode(serialize($data));
        self::run('transaction', 'notifydonation', $encoded_data);
    }

    public static function sendConfirmTransferNotification($transfer_id)
    {
        self::run('transaction', 'confirmtransfer', $transfer_id);
    }

    public static function sendTransferReceivedNotification($transfer_id)
    {
        self::run('transaction', 'notifytransfer', $transfer_id);
    }
}
