<?php

declare(strict_types=1);

namespace App\Entity\Response\App\V1\Admin;

use App\Common\Core\Entity\BaseListResponse;
use App\Model\AdminEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\ArrayType;

class AdminListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(AdminEntity::class)]
    public array $list;
}