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

namespace App\Model;

use App\Common\Constants\BaseStatus;
use App\Constants\Type\MenuType;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class MenuEntity.
 */
class MenuEntity extends \App\Common\Core\Entity\BaseModelEntity
{
    #[ApiModelProperty(value: '父级')]
    public int $pid;

    #[ApiModelProperty(value: '菜单名称')]
    public string $name;

    #[ApiModelProperty(value: '菜单类型(1-菜单；2-目录；3-外链；4-按钮权限)')]
    public MenuType $type;

    #[ApiModelProperty(value: '路由路径')]
    public string $path;

    #[ApiModelProperty(value: '组件路径(vue页面完整路径，省略.vue后缀)')]
    public string $component;

    #[ApiModelProperty(value: '权限标识')]
    public string $perm;

    #[ApiModelProperty(value: '排序')]
    public int $sort;

    #[ApiModelProperty(value: '状态')]
    public BaseStatus $status;

    #[ApiModelProperty(value: '菜单图标')]
    public string $icon;

    #[ApiModelProperty(value: '跳转路径')]
    public string $redirect;

    public array $role;
}
