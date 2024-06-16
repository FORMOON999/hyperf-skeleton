<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\RoleMenu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class RoleMenuModifyRequest
 * @package App\Controller\Admin\V1\RoleMenu\Request
 */
class RoleMenuModifyRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '角色ID')]
    public int $roleId;

    #[ApiModelProperty(value: '菜单ID')]
    public int $menuId;

}