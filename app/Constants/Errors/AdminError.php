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

class AdminError extends BaseEnum
{
    #[EnumMessage(message: '创建管理员失败')]
    public const CREATE_ERROR = 1001001;

    #[EnumMessage(message: '更新管理员失败')]
    public const UPDATE_ERROR = 1001002;

    #[EnumMessage(message: '删除管理员失败')]
    public const DELETE_ERROR = 1001003;

    #[EnumMessage(message: '管理员不存在，请重试')]
    public const NOT_FOUND = 1001004;

    #[EnumMessage(message: '数据已被占用')]
    public const EXISTS = 1001005;
}
