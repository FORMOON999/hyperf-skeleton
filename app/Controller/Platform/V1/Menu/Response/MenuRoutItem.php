<?php

namespace App\Controller\Platform\V1\Menu\Response;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;
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

    #[ApiModelProperty('子路由列表'), ArrayType(MenuRoutItem::class)]
    public array $children;
}