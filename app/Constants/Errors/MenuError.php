<?php

declare(strict_types=1);

namespace App\Constants\Errors;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;

/**
 * @method static MenuError CREATE_ERROR()
 * @method static MenuError UPDATE_ERROR()
 * @method static MenuError DELETE_ERROR()
 * @method static MenuError NOT_FOUND()
 * @method static MenuError EXISTS()
 */
class MenuError extends BaseEnum
{
    #[EnumMessage(message: "创建菜单管理失败")]
    const CREATE_ERROR = 1001001;

    #[EnumMessage(message: "更新菜单管理失败")]
    const UPDATE_ERROR = 1001002;

    #[EnumMessage(message: "删除菜单管理失败")]
    const DELETE_ERROR = 1001003;

    #[EnumMessage(message: "菜单管理不存在，请重试")]
    const NOT_FOUND = 1001004;

    #[EnumMessage(message: ":name 已被占用")]
    const EXISTS = 1001005;
}
