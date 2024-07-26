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

/**
 * 终端.
 */
enum Terminal: int
{
    use EnumMessageTrait;
    #[EnumMessage(message: '安卓')]
    case ANDROID = 1;
    #[EnumMessage(message: '苹果')]
    case IOS = 2;

    #[EnumMessage(message: '苹果书签')]
    case IOS_BOOKMARK = 3;

    #[EnumMessage(message: 'h5')]
    case PWA = 5;
}
