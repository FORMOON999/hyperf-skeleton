<?php
/**
 * Created by PhpStorm.
 * Date:  2021/9/3
 * Time:  12:15 上午.
 */

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

/**
 * 基础状态
 * @method static BaseStatus FROZEN()
 * @method static BaseStatus NORMAL()
 */
class BaseStatus extends BaseEnum
{
    /**
     * @Message("禁用")
     */
    #[EnumMessage('禁用')]
    public const FROZEN = 0;

    /**
     * @Message("正常")
     */
    #[EnumMessage('正常')]
    public const NORMAL = 1;
}
