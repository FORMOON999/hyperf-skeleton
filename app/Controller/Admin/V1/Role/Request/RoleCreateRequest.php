<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Role\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class RoleCreateRequest
 * @package App\Controller\Admin\V1\Role\Request
 */
class RoleCreateRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '角色名称', required: true), Required]
    public string $name;

    #[ApiModelProperty(value: '角色编码', required: true), Required]
    public string $code;

    #[ApiModelProperty(value: '排序', required: true), Required]
    public int $sort;

    #[ApiModelProperty(value: '状态', required: true), Required]
    public int $status;

}