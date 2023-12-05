<?php

declare(strict_types=1);

namespace App\Constants\Errors;

use Lengbin\ErrorCode\Annotation\EnumMessage;
use App\ErrorCode\BaseEnum;

class AdminError extends BaseEnum
{
    #[EnumMessage(message: "创建管理员失败")]
    const CREATE_ERROR = 1001001;

    #[EnumMessage(message: "更新管理员失败")]
    const UPDATE_ERROR = 1001002;

    #[EnumMessage(message: "删除管理员失败")]
    const DELETE_ERROR = 1001003;

    #[EnumMessage(message: "管理员不存在，请重试")]
    const NOT_FOUND = 1001004;

    #[EnumMessage(message: "数据已被占用")]
    const EXISTS = 1001005;
}
