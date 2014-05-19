<?php
/**
 * @author Tran Duc Thang
 * @date 5/18/14
 *
 */


function convert_user($user)
{
    $hkt_user = [
        $user[1],
        $user[3],
        $user[2],
        $user[4],
        10,
        "'" . sha1(microtime()) . sha1(mt_rand()) . "'",
        "'" . date('Y-m-d H:i:s') . "'",
    ];
    return $hkt_user;
}

function convert_item($item)
{
    $created_by = 1;
    $hkt_item = [
        $item[1],
        0,
        4,
        1,
        $item[2],
        1,
        "'" . date('Y-m-d H:i:s') . "'",
        $created_by,
    ];
    return $hkt_item;
}

// Users
$text = file_get_contents('users');
$preg = '/\((.*?)\)/';
preg_match_all($preg, $text, $matches);

$users = [];
foreach ($matches[1] as $user_str) {
    $user = convert_user(explode(',', $user_str));
    $users[] = '(' . implode(',', $user) . ')';
}

$str = "INSERT INTO `users` (`username`, `password`, `email`, `wallet`, `role`, `secret_key`, `created_at`) VALUES \n";
$str .= implode(",\n", $users);
file_put_contents('data_users.sql', $str);

// Items
$text = file_get_contents('items');
$preg = '/\((.*?)\)/';
preg_match_all($preg, $text, $matches);

$items = [];
$item_shops = [];
$shop_id = 1;
$start_item_id = 1;
foreach ($matches[1] as $item_str) {
    $item = convert_item(explode(',', $item_str));
    $items[] = '(' . implode(',', $item) . ')';
    $item_shops[] = '(' . $start_item_id++ . ',' . $shop_id . ',0,2,"' . date('Y-m-d H:i:s') . '")';
}

$str = "INSERT INTO `items` (`name`, `price`, `type`, `status`, `img`, `approved_by`, `created_at`, `created_by`) VALUES \n";
$str .= implode(",\n", $items) . ";\n";

$str .= "INSERT INTO `item_shops` (`item_id`, `shop_id`, `price`, `status`, `created_at`) VALUES \n";
$str .= implode(",\n", $item_shops) . "\n";

file_put_contents('data_items.sql', $str);


