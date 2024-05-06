<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Common\Core\Entity;

use App\Common\Core\BaseObject;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class BaseModelEntity extends BaseObject
{
    #[ApiModelProperty('id')]
    public int $id;

    #[ApiModelProperty('创建时间')]
    public string $createdAt;

    #[ApiModelProperty('更新时间')]
    public string $updatedAt;

    #[ApiModelProperty(value: '删除时间', hidden: true)]
    public ?string $deletedAt = null;
}
