<?php

declare(strict_types=1);

namespace App\Constants\Errors;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;

/**
 * @method static RoleError CREATE_ERROR()
 * @method static RoleError UPDATE_ERROR()
 * @method static RoleError DELETE_ERROR()
 * @method static RoleError NOT_FOUND()
 * @method static RoleError EXISTS()
 */
class RoleError extends BaseEnum
{
    #[EnumMessage(message: "创建角色管理失败")]
    const CREATE_ERROR = 1004001;

    #[EnumMessage(message: "更新角色管理失败")]
    const UPDATE_ERROR = 1004002;

    #[EnumMessage(message: "删除角色管理失败")]
    const DELETE_ERROR = 1004003;

    #[EnumMessage(message: "角色管理不存在，请重试")]
    const NOT_FOUND = 1004004;

    #[EnumMessage(message: ":name 已被占用")]
    const EXISTS = 1004005;
}
