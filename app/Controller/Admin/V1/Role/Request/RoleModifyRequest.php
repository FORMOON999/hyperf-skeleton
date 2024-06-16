<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Role\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class RoleModifyRequest
 * @package App\Controller\Admin\V1\Role\Request
 */
class RoleModifyRequest extends \App\Common\Core\BaseObject
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