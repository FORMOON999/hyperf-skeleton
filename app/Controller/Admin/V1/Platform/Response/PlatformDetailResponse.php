<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Platform\Response;

use App\Common\Core\Annotation\ArrayType;
use App\Model\PlatformEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class PlatformDetailResponse extends PlatformEntity
{
    #[ApiModelProperty(value: '角色'), ArrayType(type: 'string')]
    public array $roles;
}