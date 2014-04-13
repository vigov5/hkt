<?php
/**
 * @author Tran Duc Thang
 * @date 4/13/14
 *
 */

class Keygen
{
    public static function generateKey()
    {
        $key = '';
        for ($i = 0; $i < 100; $i++) {
            $salt = uniqid(mt_rand(0, microtime(true)), true);
            $key = sha1($key . $salt);
        }
        return $key;
    }
}
