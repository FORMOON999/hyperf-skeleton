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

namespace App\Constants\Errors;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;

/**
 * @method static PlatformError CREATE_ERROR()
 * @method static PlatformError UPDATE_ERROR()
 * @method static PlatformError DELETE_ERROR()
 * @method static PlatformError NOT_FOUND()
 * @method static PlatformError EXISTS()
 * @method static PlatformError CAPTCHA_ERROR()
 * @method static PlatformError ACCOUNT_OR_PASSWORD_NOT_FOUND()
 * @method static PlatformError FROZEN()
 */
class PlatformError extends BaseEnum
{
    #[EnumMessage(message: '创建管理员失败')]
    public const CREATE_ERROR = 1002001;

    #[EnumMessage(message: '更新管理员失败')]
    public const UPDATE_ERROR = 1002002;

    #[EnumMessage(message: '删除管理员失败')]
    public const DELETE_ERROR = 1002003;

    #[EnumMessage(message: '管理员不存在，请重试')]
    public const NOT_FOUND = 1002004;

    #[EnumMessage(message: ':name 已被占用')]
    public const EXISTS = 1002005;

    #[EnumMessage(message: '验证码错误')]
    public const CAPTCHA_ERROR = 1002006;

    #[EnumMessage(message: '账号或者密码错误')]
    public const ACCOUNT_OR_PASSWORD_NOT_FOUND = 1002007;

    #[EnumMessage(message: '账号已冻结，请联系管理员')]
    public const FROZEN = 1002008;
}
