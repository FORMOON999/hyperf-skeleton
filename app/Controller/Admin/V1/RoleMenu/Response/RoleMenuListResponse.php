<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\RoleMenu\Response;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use App\Common\Core\Annotation\ArrayType;
use App\Model\RoleMenuEntity;

class RoleMenuListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(RoleMenuEntity::class)]
    public array $list;
}