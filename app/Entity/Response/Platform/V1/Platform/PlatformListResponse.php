<?php

declare(strict_types=1);

namespace App\Entity\Response\Platform\V1\Platform;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use App\Model\PlatformEntity;
use Lengbin\Common\Annotation\ArrayType;

class PlatformListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(PlatformEntity::class)]
    public array $list;
}