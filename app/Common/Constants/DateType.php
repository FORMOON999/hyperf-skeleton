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

namespace App\Common\Constants;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;
use DateTime;
use Exception;
use Lengbin\Helper\Util\DateHelper;

/**
 * @method static DateType DAILY()
 * @method static DateType WEEKLY()
 * @method static DateType MONTHLY()
 * @method static DateType YEARLY()
 * @method static DateType HOURS()
 */
class DateType extends BaseEnum
{
    /**
     * @Message("日")
     */
    #[EnumMessage('日')]
    public const DAILY = 1;

    /**
     * @Message("周")
     */
    #[EnumMessage('周')]
    public const WEEKLY = 2;

    /**
     * @Message("月")
     */
    #[EnumMessage('月')]
    public const MONTHLY = 3;

    /**
     * @Message("年")
     */
    #[EnumMessage('年')]
    public const YEARLY = 5;

    /**
     * @Message("小时")
     */
    #[EnumMessage('小时')]
    public const HOURS = 6;

    /**
     * 获取周期开始时间.
     */
    public static function getStartAt(DateType $type, int $time): int
    {
        switch ($type->getValue()) {
            case DateType::DAILY:
                return strtotime(date('Ymd 00:00:00', $time));
            case DateType::WEEKLY:
                return strtotime(date('Ymd 00:00:00', strtotime('this week Monday', $time)));
            case DateType::MONTHLY:
                return strtotime(date('Ym01 00:00:00', $time));
            case DateType::YEARLY:
                return strtotime(date('Y0101 00:00:00', $time));
            case DateType::HOURS:
                return $time;
        }
        return 0;
    }

    /**
     * 获取周期结束时间.
     * @throws Exception
     */
    public static function getEntAt(DateType $type, int $startAt): int
    {
        switch ($type->getValue()) {
            case DateType::DAILY:
                return $startAt + DateHelper::DAY;
            case DateType::WEEKLY:
                return $startAt + DateHelper::DAY * 7;
            case DateType::MONTHLY:
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of next month')->getTimestamp();
            case DateType::YEARLY:
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of next year')->getTimestamp();
            case DateType::HOURS:
                return time();
        }
        return 0;
    }

    /**
     * 获取上一周期开始时间.
     * @throws Exception
     */
    public static function getLastStartAt(DateType $type, int $startAt): int
    {
        switch ($type->getValue()) {
            case DateType::DAILY:
                return $startAt - DateHelper::DAY;
            case DateType::WEEKLY:
                return $startAt - DateHelper::DAY * 7;
            case DateType::MONTHLY:
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of last month')->getTimestamp();
            case DateType::YEARLY:
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of last year')->getTimestamp();
        }
        return 0;
    }

    public static function isToday(DateType $type, int $date): bool
    {
        return $date >= self::getStartAt($type, time());
    }
}
