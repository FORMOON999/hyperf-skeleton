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

/**
 * @method static CommonError SUCCESS()
 * @method static CommonError SERVER_ERROR()
 * @method static CommonError INVALID_PERMISSION()
 * @method static CommonError INVALID_PARAMS()
 * @method static CommonError INVALID_TOKEN()
 */
class CommonError extends BaseEnum
{
    /**
     * @Message("Success")
     */
    #[EnumMessage('Success')]
    public const SUCCESS = 0;

    /**
     * @Message("系统错误")
     */
    #[EnumMessage('系统错误')]
    public const SERVER_ERROR = 500;

    /**
     * @Message("无效权限")
     */
    #[EnumMessage('无效权限')]
    public const INVALID_PERMISSION = 403;

    /**
     * @Message("错误的请求参数")
     */
    #[EnumMessage('错误的请求参数')]
    public const INVALID_PARAMS = 400;

    /**
     * @Message("请重新登录")
     */
    #[EnumMessage('请重新登录')]
    public const INVALID_TOKEN = 401;
}
