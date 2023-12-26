<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\PlatformLoginRecord;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;
use Lengbin\Common\Entity\Page;

class PlatformLoginRecordListRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数')]
    public ?PlatformLoginRecordListSearch $search = null;

    #[ApiModelProperty('分页参数')]
    public ?Page $page = null;

    #[ApiModelProperty('排序参数')]
    public ?PlatformLoginRecordListSort $sort = null;
}