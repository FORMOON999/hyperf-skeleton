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

namespace App\Controller\Platform\V1\Menu\Request;

use App\Common\Constants\BaseStatus;
use App\Constants\Type\MenuType;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Hyperf\DTO\Annotation\Validation\Validation;

/**
 * Class MenuCreateRequest.
 */
class MenuCreateRequest extends \Lengbin\Common\BaseObject
{
    #[ApiModelProperty(value: '父级', required: true), Required]
    public int $pid;

    #[ApiModelProperty(value: '菜单名称', required: true), Required]
    public string $name;

    #[ApiModelProperty(value: '菜单类型(1-菜单；2-目录；3-外链；4-按钮权限)', required: true), Required]
    public MenuType $type;

    #[ApiModelProperty(value: '路由路径', required: true)]
    public string $path;

    #[ApiModelProperty(value: '组件路径(vue页面完整路径，省略.vue后缀)', required: true)]
    public string $component;

    #[ApiModelProperty(value: '权限标识', required: true)]
    public string $perm;

    #[ApiModelProperty(value: '排序', required: true), Required]
    public int $sort;

    #[ApiModelProperty(value: '状态', required: true)]
    public BaseStatus $status;

    #[ApiModelProperty(value: '菜单图标', required: true)]
    public string $icon;

    #[ApiModelProperty(value: '跳转路径', required: true)]
    public string $redirect;
}
