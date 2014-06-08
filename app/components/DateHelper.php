<?php
/**
 * @author Tran Duc Thang
 *
 */

class DateHelper
{
    public static $format = 'Y-m-d H:i:s';
    public static $day_format = 'Y-m-d';

    public static function now()
    {
        return date(self::$format);
    }

    public static function today()
    {
        return date(self::$day_format);
    }

    public static function day_before($day_num, $date = null)
    {
        if (!self::isValidDate($date)) {
            $date = self::now();
        }
        return date(self::$day_format, strtotime("-{$day_num} days", strtotime($date)));
    }

    public static function day_after($day_num, $date = null)
    {
        if (!self::isValidDate($date)) {
            $date = self::now();
        }
        return date(self::$day_format, strtotime("+{$day_num} days", strtotime($date)));
    }

    public static function dateOnly($date_time)
    {
        return date(self::$day_format, strtotime($date_time));
    }

    public static function isValidDate($date)
    {
        if (!$date) {
            return false;
        }
        $date = trim($date);
        $time = strtotime($date);

        return date(self::$day_format, $time) == $date;
    }
}