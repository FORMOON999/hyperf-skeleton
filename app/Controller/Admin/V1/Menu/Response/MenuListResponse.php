<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Menu\Response;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use App\Common\Core\Annotation\ArrayType;
use App\Model\MenuEntity;

class MenuListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(MenuEntity::class)]
    public array $list;
}