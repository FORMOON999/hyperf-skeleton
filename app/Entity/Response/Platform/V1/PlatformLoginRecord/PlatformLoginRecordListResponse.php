<?php

declare(strict_types=1);

namespace App\Entity\Response\Platform\V1\PlatformLoginRecord;

use App\Common\Core\Entity\BaseListResponse;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use App\Model\PlatformLoginRecordEntity;
use Lengbin\Common\Annotation\ArrayType;

class PlatformLoginRecordListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(className: PlatformLoginRecordEntity::class)]
    public array $list;
}