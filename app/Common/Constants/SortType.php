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

namespace App\Common\Constants;

use App\Common\Core\Enum\Annotation\EnumMessage;
use App\Common\Core\Enum\BaseEnum;

/**
 * 排序方式.
 * @method static SortType UNKNOWN()
 * @method static SortType ASC()
 * @method static SortType DESC()
 */
class SortType extends BaseEnum
{
    /**
     * @Message("")
     */
    #[EnumMessage(message: '')]
    public const UNKNOWN = '';

    /**
     * @Message("正序")
     */
    #[EnumMessage(message: '正序')]
    public const ASC = 'asc';

    /**
     * @Message("倒序")
     */
    #[EnumMessage(message: '倒序')]
    public const DESC = 'desc';
}
