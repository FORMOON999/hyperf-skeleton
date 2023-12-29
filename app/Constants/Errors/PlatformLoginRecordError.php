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
 * @method static PlatformLoginRecordError CREATE_ERROR()
 * @method static PlatformLoginRecordError UPDATE_ERROR()
 * @method static PlatformLoginRecordError DELETE_ERROR()
 * @method static PlatformLoginRecordError NOT_FOUND()
 * @method static PlatformLoginRecordError EXISTS()
 */
class PlatformLoginRecordError extends BaseEnum
{
    #[EnumMessage(message: '创建管理员登录日志失败')]
    public const CREATE_ERROR = 1002001;

    #[EnumMessage(message: '更新管理员登录日志失败')]
    public const UPDATE_ERROR = 1002002;

    #[EnumMessage(message: '删除管理员登录日志失败')]
    public const DELETE_ERROR = 1002003;

    #[EnumMessage(message: '管理员登录日志不存在，请重试')]
    public const NOT_FOUND = 1002004;

    #[EnumMessage(message: '数据已被占用')]
    public const EXISTS = 1002005;
}
