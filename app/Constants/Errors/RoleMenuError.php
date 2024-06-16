<?php

declare(strict_types=1);

namespace App\Constants\Errors;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;

/**
 * @method static RoleMenuError CREATE_ERROR()
 * @method static RoleMenuError UPDATE_ERROR()
 * @method static RoleMenuError DELETE_ERROR()
 * @method static RoleMenuError NOT_FOUND()
 * @method static RoleMenuError EXISTS()
 */
class RoleMenuError extends BaseEnum
{
    #[EnumMessage(message: "创建角色菜单关联失败")]
    const CREATE_ERROR = 1005001;

    #[EnumMessage(message: "更新角色菜单关联失败")]
    const UPDATE_ERROR = 1005002;

    #[EnumMessage(message: "删除角色菜单关联失败")]
    const DELETE_ERROR = 1005003;

    #[EnumMessage(message: "角色菜单关联不存在，请重试")]
    const NOT_FOUND = 1005004;

    #[EnumMessage(message: ":name 已被占用")]
    const EXISTS = 1005005;
}
