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
 * 终端.
 * @method static Terminal IOS()
 * @method static Terminal ANDROID()
 * @method static Terminal PC()
 * @method static Terminal H5()
 */
class Terminal extends BaseEnum
{
    #[EnumMessage(message: '苹果')]
    public const IOS = 1;

    #[EnumMessage(message: '安卓')]
    public const ANDROID = 2;

    #[EnumMessage(message: '苹果flutter')]
    public const IOS_FLUTTER = 3;

    #[EnumMessage(message: '安卓flutter')]
    public const ANDROID_FLUTTER = 4;

    #[EnumMessage(message: 'h5')]
    public const PWA = 5;
}
