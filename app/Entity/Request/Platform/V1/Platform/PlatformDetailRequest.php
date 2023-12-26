<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\Platform;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

class PlatformDetailRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数'), Required]
    public PlatformSearch $search;
}