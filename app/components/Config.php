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

    const DATE_FORMAT = 'Y-m-d';
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    const MAX_IMG_FILE_SIZE = 1000000; // 1MB

    public static function getFullImageUploadDir()
    {
        return __DIR__ . '/../../public/' . static::IMG_UPLOAD_DIR;
    }
}
