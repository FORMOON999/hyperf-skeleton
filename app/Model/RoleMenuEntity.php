<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class RoleMenuEntity
 * @package App\Model
 */
class RoleMenuEntity extends \App\Common\Core\Entity\BaseModelEntity
{

    #[ApiModelProperty(value: '角色ID')]
    public int $roleId;

    #[ApiModelProperty(value: '菜单ID')]
    public int $menuId;

}