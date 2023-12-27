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

namespace App\Controller\Platform\V1\PlatformLoginRecord\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class PlatformLoginRecordSearch.
 */
class PlatformLoginRecordSearch extends \Lengbin\Common\BaseObject
{
    #[ApiModelProperty('管理员登录日志ID'), Required]
    public int $id;
}
