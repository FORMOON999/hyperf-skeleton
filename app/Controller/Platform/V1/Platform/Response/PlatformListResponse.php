<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Platform\Response;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;
use App\Model\PlatformEntity;

class PlatformListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(PlatformEntity::class)]
    public array $list;
}