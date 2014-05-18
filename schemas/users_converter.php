<?php
/**
 * @author Tran Duc Thang
 * @date 5/18/14
 *
 */

function convert($user)
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

$text = file_get_contents('users');
$preg = '/\((.*?)\)/';
preg_match_all($preg, $text, $matches);

$users = [];
foreach ($matches[1] as $user_str) {
    $user = convert(explode(',', $user_str));
    $users[] = '(' . implode(',', $user) . ')';
}

$str = "INSERT INTO `users` (`username`, `password`, `email`, `wallet`, `role`, `secret_key`, `created_at`) VALUES \n";
$str .= implode(",\n", $users);
file_put_contents('data_users.sql', $str);



