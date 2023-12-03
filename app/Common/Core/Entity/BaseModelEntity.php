<?php

namespace App\Common\Core\Entity;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;

class BaseModelEntity extends BaseObject
{
    #[ApiModelProperty('id')]
    public ?int $id = null;

    #[ApiModelProperty('创建时间')]
    public ?string $createdAt = null;

    #[ApiModelProperty('更新时间')]
    public ?string $updatedAt = null;

    #[ApiModelProperty('删除时间')]
    public ?string $deletedAt = null;
}