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
}