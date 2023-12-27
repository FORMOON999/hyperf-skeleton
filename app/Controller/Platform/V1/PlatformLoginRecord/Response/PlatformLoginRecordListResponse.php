<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\PlatformLoginRecord\Response;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;
use App\Model\PlatformLoginRecordEntity;

class PlatformLoginRecordListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(PlatformLoginRecordEntity::class)]
    public array $list;
}