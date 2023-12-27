<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Platform\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

class PlatformRemoveRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数'), Required]
    public PlatformRemoveSearch $search;
}