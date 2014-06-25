<?php
/**
 * @author Nguyen Anh Tien
 * @date 26/06/2014
 *
 */

class CryptoHelper
{

    /**
     *  generate random length string
     * @param int length
     */
    public static function generateRandomString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateRandomNumber(){
        return uniqid(mt_rand(0, microtime(true)), true);
    }

    /**
     *  generate HMAC signature of data
     * @param int length
     */
    public static function calculateHMAC($key, $data){
        return hash_hmac("sha256", $data, $key);
    }
}
