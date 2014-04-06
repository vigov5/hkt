<?php
/**
 * @author Tran Duc Thang
 * @date 4/6/14
 *
 */

class UrlHelper {
    public static function isValidUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
} 