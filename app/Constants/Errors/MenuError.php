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
 * @method static MenuError CREATE_ERROR()
 * @method static MenuError UPDATE_ERROR()
 * @method static MenuError DELETE_ERROR()
 * @method static MenuError NOT_FOUND()
 * @method static MenuError EXISTS()
 */
class MenuError extends BaseEnum
{
    #[EnumMessage(message: '创建菜单管理失败')]
    public const CREATE_ERROR = 1004001;

    #[EnumMessage(message: '更新菜单管理失败')]
    public const UPDATE_ERROR = 1004002;

    #[EnumMessage(message: '删除菜单管理失败')]
    public const DELETE_ERROR = 1004003;

    #[EnumMessage(message: '菜单管理不存在，请重试')]
    public const NOT_FOUND = 1004004;

    #[EnumMessage(message: '数据已被占用')]
    public const EXISTS = 1004005;
}
