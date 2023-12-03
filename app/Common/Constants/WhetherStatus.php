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

/**
 * 是否.
 * @method static WhetherStatus YES()
 * @method static WhetherStatus NO()
 */
class WhetherStatus extends BaseEnum
{
    /**
     * @Message("是")
     */
    #[EnumMessage('是')]
    public const YES = 1;

    /**
     * @Message("否")
     */
    #[EnumMessage('否')]
    public const NO = 0;
}
