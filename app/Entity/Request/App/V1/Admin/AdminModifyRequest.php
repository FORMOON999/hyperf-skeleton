<?php

declare(strict_types=1);

namespace App\Entity\Request\App\V1\Admin;

use App\Model\AdminEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

class AdminModifyRequest extends BaseObject
{
    #[ApiModelProperty('控制参数')]
    public ?AdminCondition $condition = null;

    #[ApiModelProperty('搜索参数'), Required]
    public AdminSearch $search;

    #[ApiModelProperty('请求数据'), Required]
    public AdminEntity $data;
}