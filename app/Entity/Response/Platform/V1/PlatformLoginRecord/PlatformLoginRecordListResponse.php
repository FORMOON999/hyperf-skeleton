<?php

declare(strict_types=1);

namespace App\Entity\Response\Platform\V1\PlatformLoginRecord;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\ArrayType;
use App\Model\PlatformLoginRecordEntity;

class PlatformLoginRecordListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(PlatformLoginRecordEntity::class)]
    public array $list;
}