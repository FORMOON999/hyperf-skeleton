<?php

declare(strict_types=1);

namespace App\Constants\Errors;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;

class PlatformLoginRecordError extends BaseEnum
{
    #[EnumMessage(message: "创建管理台登录日志失败")]
    const CREATE_ERROR = 1002001;

    #[EnumMessage(message: "更新管理台登录日志失败")]
    const UPDATE_ERROR = 1002002;

    #[EnumMessage(message: "删除管理台登录日志失败")]
    const DELETE_ERROR = 1002003;

    #[EnumMessage(message: "管理台登录日志不存在，请重试")]
    const NOT_FOUND = 1002004;

    #[EnumMessage(message: "数据已被占用")]
    const EXISTS = 1002005;
}
