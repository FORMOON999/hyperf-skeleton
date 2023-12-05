<?php

declare(strict_types=1);

namespace App\Entity\Request\App\V1\Admin;

use App\Common\Core\Entity\BaseSort;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;
use Lengbin\Common\Entity\Page;

class AdminListRequest extends BaseObject
{
    #[ApiModelProperty('控制参数')]
    public ?AdminCondition $condition = null;

    #[ApiModelProperty('搜索参数')]
    public ?AdminListSearch $search = null;

    #[ApiModelProperty('分页参数')]
    public Page $page;

    #[ApiModelProperty('排序参数')]
    public ?BaseSort $sort = null;
}