<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\PlatformLoginRecord;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;
use App\Model\PlatformLoginRecordEntity;


class PlatformLoginRecordCreateRequest extends BaseObject
{
    #[ApiModelProperty('控制参数')]
    public ?PlatformLoginRecordCondition $condition = null;

    #[ApiModelProperty('请求数据'), Required]
    public PlatformLoginRecordEntity $data;
}