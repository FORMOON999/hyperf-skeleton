<?php

declare(strict_types=1);

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
    #[EnumMessage(message: "创建管理员登录日志失败")]
    const CREATE_ERROR = 1003001;

    #[EnumMessage(message: "更新管理员登录日志失败")]
    const UPDATE_ERROR = 1003002;

    #[EnumMessage(message: "删除管理员登录日志失败")]
    const DELETE_ERROR = 1003003;

    #[EnumMessage(message: "管理员登录日志不存在，请重试")]
    const NOT_FOUND = 1003004;

    #[EnumMessage(message: ":name 已被占用")]
    const EXISTS = 1003005;
}
