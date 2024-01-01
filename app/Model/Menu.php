<?php

declare (strict_types=1);
namespace App\Model;

use App\Common\Core\BaseModel;
use App\Common\Core\Entity\BaseModelEntity;
/**
 * @property int $id 
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 * @property int $pid 父级
 * @property string $name 菜单名称
 * @property int $type 菜单类型(1-菜单；2-目录；3-外链；4-按钮权限)
 * @property string $path 路由路径
 * @property string $component 组件路径(vue页面完整路径，省略.vue后缀)
 * @property string $perm 路由路径
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $icon 菜单图标
 * @property string $redirect 跳转路径
 */
class Menu extends BaseModel
{
    /**
     * primaryKey
     *
     * @var string
     */
    protected string $primaryKey = 'id';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected ?string $table = 'menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = ['id', 'created_at', 'updated_at', 'deleted_at', 'pid', 'name', 'type', 'path', 'component', 'perm', 'sort', 'status', 'icon', 'redirect'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'pid' => 'integer', 'type' => 'integer', 'sort' => 'integer', 'status' => 'integer'];
    /**
     * @return BaseModelEntity
     */
    public function newEntity() : BaseModelEntity
    {
        return new MenuEntity($this->toArray());
    }
}