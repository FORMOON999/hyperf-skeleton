<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\RoleMenu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class RoleMenuRemoveRequest
 * @package App\Controller\Admin\V1\RoleMenu\Request
 */
class RoleMenuRemoveRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '角色菜单关联ID', required: true), Required]
    public int $id;

}