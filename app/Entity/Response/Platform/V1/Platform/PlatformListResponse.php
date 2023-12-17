<?php

declare(strict_types=1);

namespace App\Entity\Response\Platform\V1\Platform;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\ArrayType;
use App\Model\PlatformEntity;

class PlatformListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(PlatformEntity::class)]
    public array $list;
}