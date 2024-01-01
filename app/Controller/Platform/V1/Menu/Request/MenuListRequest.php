<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Menu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;
use Lengbin\Common\Entity\Page;

class MenuListRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数')]
    public ?MenuListSearch $search = null;

    #[ApiModelProperty('分页参数')]
    public ?Page $page = null;

    #[ApiModelProperty('排序参数')]
    public ?MenuListSort $sort = null;
}