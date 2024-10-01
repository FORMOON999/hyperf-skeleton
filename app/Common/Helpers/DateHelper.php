<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Common\Helpers;

use DateTime;
use Exception;

class DateHelper
{
    public const MINUTE = 60;

    public const HOUR = 3600;

    public const DAY = 24 * 3600;

    public const MONTH = 30 * 24 * 3600;

    public static function getTodayZero()
    {
        return strtotime(date('Y-m-d 00:00:00', time()));
    }

    /**
     * @throws Exception
     */
    public static function gmt_iso8601(int $time): string
    {
        $dtStr = date('c', $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration . 'Z';
    }

    /**
     * 人性化时间显示.
     *
     * @param int $time
     *
     * @return false|string
     */
    public static function formatTime($time)
    {
        $rtime = date('Y-m-d H:i:s', $time);
        $time = time() - $time;
        if ($time < 60) {
            $str = '刚刚';
        } elseif ($time < 60 * 60) {
            $min = floor($time / 60);
            $str = $min . '分钟前';
        } elseif ($time < 60 * 60 * 24) {
            $h = floor($time / (60 * 60));
            $str = $h . '小时前 ';
        } elseif ($time < 60 * 60 * 24 * 3) {
            $d = floor($time / (60 * 60 * 24));
            if ($d == 1) {
                $str = '昨天 ' . $rtime;
            } else {
                $str = '前天 ' . $rtime;
            }
        } else {
            $str = $rtime;
        }
        return $str;
    }

    // 判断一个变量 是否为日期字符串
    public static function isDate(DateTime|string $var): bool
    {
        if ($var instanceof DateTime) {
            return true;
        }

        $d = DateTime::createFromFormat('Y-m-d H:i:s', $var);
        return $d && $d->format('Y-m-d H:i:s') === $var;
    }

    // 检查是否是数字，并且是一个有效的 Unix 时间戳范围
    public static function isTimestamp($var): bool
    {
        return is_numeric($var) && (int) $var == $var && (int) $var > 0 && (int) $var <= PHP_INT_MAX;
    }

    public static function getDate($var, string $format = 'Y-m-d H:i:s'): string
    {
        if (self::isDate($var)) {
            return $var;
        }

        if (self::isTimestamp($var)) {
            return date($format, $var);
        }

        return '';
    }

    public static function getTimestamp($var): int
    {
        if (self::isTimestamp($var)) {
            return $var;
        }
        if (self::isDate($var)) {
            return strtotime($var);
        }
        return 0;
    }
}
