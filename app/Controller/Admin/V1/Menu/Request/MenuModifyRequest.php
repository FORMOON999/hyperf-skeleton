<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Menu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class MenuModifyRequest
 * @package App\Controller\Admin\V1\Menu\Request
 */
class MenuModifyRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '父级')]
    public int $pid;

    #[ApiModelProperty(value: '菜单名称')]
    public string $name;

    #[ApiModelProperty(value: '菜单类型(1-菜单；2-目录；3-外链；4-按钮权限)')]
    public int $type;

    #[ApiModelProperty(value: '路由路径')]
    public string $path;

    #[ApiModelProperty(value: '组件路径(vue页面完整路径，省略.vue后缀)')]
    public string $component;

    #[ApiModelProperty(value: '权限标识')]
    public string $perm;

    #[ApiModelProperty(value: '排序')]
    public int $sort;

    #[ApiModelProperty(value: '状态')]
    public int $status;

    #[ApiModelProperty(value: '菜单图标')]
    public string $icon;

    #[ApiModelProperty(value: '跳转路径')]
    public string $redirect;

}