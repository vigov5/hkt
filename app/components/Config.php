<?php
/**
 * @author Tran Duc Thang
 * @date 4/6/14
 *
 */

class Config
{
    const PRODUCT = false;
    const IMG_UPLOAD_DIR = 'img/upload/';

    const MAX_IMG_FILE_SIZE = 1000000; // 1MB

    public static function getFullImageUploadDir()
    {
        return __DIR__ . '/../../public/' . static::IMG_UPLOAD_DIR;
    }
}
