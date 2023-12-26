<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\Platform;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;
use Lengbin\Common\Entity\Page;

class PlatformListRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数')]
    public ?PlatformListSearch $search = null;

    #[ApiModelProperty('分页参数')]
    public ?Page $page = null;

    #[ApiModelProperty('排序参数')]
    public ?PlatformListSort $sort = null;
}