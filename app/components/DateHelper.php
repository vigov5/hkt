<?php
/**
 * @author Tran Duc Thang
 *
 */

class DateHelper
{
    public static $format = 'Y-m-d H:i:s';
    public static $date_format = 'Y-m-d';

    public static function now()
    {
        return date(self::$format);
    }

    public static function today()
    {
        return date(self::$date_format);
    }

    public static function dateOnly($date_time)
    {
        return date(self::$date_format, strtotime($date_time));
    }

    public static function isValidDate($date)
    {
        $date = trim($date);
        $time = strtotime($date);

        return date(self::$date_format, $time) == $date;
    }
}