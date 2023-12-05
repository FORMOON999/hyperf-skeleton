<?php

declare(strict_types=1);

namespace App\Entity\Response\Platform\V1\Admin;

use App\Common\Entity\Response\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;

class AdminListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(className: AdminItem::class)]
    public array $list;
}