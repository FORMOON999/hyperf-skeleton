<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\Admin;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

class AdminRemoveRequest extends BaseObject
{
    #[ApiModelProperty('控制参数')]
    public ?AdminCondition $condition = null;

    #[ApiModelProperty('搜索参数'), Required]
    public AdminRemoveSearch $search;
}