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
use Lengbin\Common\BaseObject;

class MenuRoutItem extends BaseObject
{
    #[ApiModelProperty(value: '路由路径')]
    public string $path;

    #[ApiModelProperty(value: '组件路径(vue页面完整路径，省略.vue后缀)')]
    public string $component;

    #[ApiModelProperty(value: '跳转路径')]
    public string $redirect;

    #[ApiModelProperty(value: '路由名称')]
    public string $name;

    #[ApiModelProperty(value: '路由属性类型')]
    public MenuRoutMeta $meta;

    #[ApiModelProperty('子路由列表')]
    public array $children;
}
