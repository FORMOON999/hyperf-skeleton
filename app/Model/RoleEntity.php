<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class RoleEntity
 * @package App\Model
 */
class RoleEntity extends \App\Common\Core\Entity\BaseModelEntity
{

    #[ApiModelProperty(value: '角色名称')]
    public string $name;

    #[ApiModelProperty(value: '角色编码')]
    public string $code;

    #[ApiModelProperty(value: '排序')]
    public int $sort;

    #[ApiModelProperty(value: '状态')]
    public int $status;

}