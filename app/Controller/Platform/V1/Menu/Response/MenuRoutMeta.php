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

namespace App\Controller\Platform\V1\Menu\Response;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;
use Lengbin\Common\BaseObject;

class MenuRoutMeta extends BaseObject
{
    #[ApiModelProperty(value: '路由title')]
    public string $title;

    #[ApiModelProperty(value: '菜单图标')]
    public string $icon;

    #[ApiModelProperty(value: '是否隐藏')]
    public bool $hidden;

//    #[ApiModelProperty(value: '拥有路由权限的角色编码'), ArrayType(type: 'string')]
//    public array $roles;

    public bool $keepAlive = true;
}
