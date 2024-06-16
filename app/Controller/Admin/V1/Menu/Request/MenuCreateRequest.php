<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Menu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class MenuCreateRequest
 * @package App\Controller\Admin\V1\Menu\Request
 */
class MenuCreateRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '父级', required: true), Required]
    public int $pid;

    #[ApiModelProperty(value: '菜单名称', required: true), Required]
    public string $name;

    #[ApiModelProperty(value: '菜单类型(1-菜单；2-目录；3-外链；4-按钮权限)', required: true), Required]
    public int $type;

    #[ApiModelProperty(value: '路由路径', required: true), Required]
    public string $path;

    #[ApiModelProperty(value: '组件路径(vue页面完整路径，省略.vue后缀)', required: true), Required]
    public string $component;

    #[ApiModelProperty(value: '权限标识', required: true), Required]
    public string $perm;

    #[ApiModelProperty(value: '排序', required: true), Required]
    public int $sort;

    #[ApiModelProperty(value: '状态', required: true), Required]
    public int $status;

    #[ApiModelProperty(value: '菜单图标', required: true), Required]
    public string $icon;

    #[ApiModelProperty(value: '跳转路径', required: true), Required]
    public string $redirect;

}