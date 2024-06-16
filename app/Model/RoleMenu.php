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
 * @property int $role_id 角色ID
 * @property int $menu_id 菜单ID
 */
class RoleMenu extends BaseModel
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
    protected ?string $table = 'role_menu';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = ['id', 'created_at', 'updated_at', 'deleted_at', 'role_id', 'menu_id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'role_id' => 'integer', 'menu_id' => 'integer'];
    /**
     * @return BaseModelEntity
     */
    public function newEntity() : BaseModelEntity
    {
        return new RoleMenuEntity($this->toArray());
    }
}