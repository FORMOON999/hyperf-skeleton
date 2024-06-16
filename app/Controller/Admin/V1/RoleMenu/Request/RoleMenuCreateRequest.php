<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\RoleMenu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class RoleMenuCreateRequest
 * @package App\Controller\Admin\V1\RoleMenu\Request
 */
class RoleMenuCreateRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '角色ID', required: true), Required]
    public int $roleId;

    #[ApiModelProperty(value: '菜单ID', required: true), Required]
    public int $menuId;

}