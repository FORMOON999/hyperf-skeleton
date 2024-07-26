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
use App\Common\Core\Enum\EnumMessageTrait;
use App\Common\Helpers\DateHelper;
use DateTime;
use Exception;

enum DateType: int
{
    use EnumMessageTrait;

    /**
     * @Message("日")
     */
    #[EnumMessage('日')]
    case DAILY = 1;

    /**
     * @Message("周")
     */
    #[EnumMessage('周')]
    case WEEKLY = 2;

    /**
     * @Message("月")
     */
    #[EnumMessage('月')]
    case MONTHLY = 3;

    /**
     * @Message("年")
     */
    #[EnumMessage('年')]
    case YEARLY = 5;

    /**
     * @Message("小时")
     */
    #[EnumMessage('小时')]
    case HOURS = 6;

    /**
     * 获取周期开始时间.
     */
    public static function getStartAt(DateType $type, int $time): int
    {
        switch ($type->value) {
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
        return match ($type) {
            self::DAILY => $startAt + DateHelper::DAY,
            self::WEEKLY => $startAt + DateHelper::DAY * 7,
            self::MONTHLY => function () use ($startAt) {
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of next month')->getTimestamp();
            },
            self::YEARLY => function () use ($startAt) {
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of next year')->getTimestamp();
            },
            self::HOURS => time(),
            default => 0,
        };
    }

    /**
     * 获取上一周期开始时间.
     * @throws Exception
     */
    public static function getLastStartAt(DateType $type, int $startAt): int
    {
        return match ($type) {
            self::DAILY => $startAt - DateHelper::DAY,
            self::WEEKLY => $startAt - DateHelper::DAY * 7,
            self::MONTHLY => function () use ($startAt) {
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of last month')->getTimestamp();
            },
            self::YEARLY => function () use ($startAt) {
                $datetime = new DateTime(date('Ym01', $startAt));
                return $datetime->modify('first day of last year')->getTimestamp();
            },
            default => 0,
        };
    }

    public static function isToday(DateType $type, int $date): bool
    {
        return $date >= self::getStartAt($type, time());
    }
}
