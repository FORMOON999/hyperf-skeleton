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

namespace App\Controller\Platform\V1\Role\Response;

use App\Common\Core\Entity\BaseListResponse;
use App\Model\RoleEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;

class RoleListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(RoleEntity::class)]
    public array $list;
}
