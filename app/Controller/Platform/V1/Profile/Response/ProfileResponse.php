<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Profile\Response;

use App\Model\PlatformEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;

class ProfileResponse extends PlatformEntity
{
    #[ApiModelProperty(value: '权限'), ArrayType(type: 'string')]
    public array $perms;
}