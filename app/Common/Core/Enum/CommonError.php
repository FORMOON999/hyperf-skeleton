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

namespace App\Common\Core\Enum;

use App\Common\Core\Enum\Annotation\EnumMessage;

enum CommonError: int implements MessageBackedEnum
{
    use EnumMessageTrait;

    /**
     * @Message("Success")
     */
    #[EnumMessage('Success')]
    case SUCCESS = 0;

    /**
     * @Message("系统错误")
     */
    #[EnumMessage('系统错误')]
    case SERVER_ERROR = 500;

    /**
     * @Message("无效权限")
     */
    #[EnumMessage('无效权限')]
    case INVALID_PERMISSION = 403;

    /**
     * @Message("错误的请求参数")
     */
    #[EnumMessage('错误的请求参数')]
    case INVALID_PARAMS = 400;

    /**
     * @Message("请重新登录")
     */
    #[EnumMessage('请重新登录')]
    case INVALID_TOKEN = 401;
}
