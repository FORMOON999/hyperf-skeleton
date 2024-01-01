<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Role\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;
use Lengbin\Common\Entity\Page;

class RoleListRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数')]
    public ?RoleListSearch $search = null;

    #[ApiModelProperty('分页参数')]
    public ?Page $page = null;

    #[ApiModelProperty('排序参数')]
    public ?RoleListSort $sort = null;
}