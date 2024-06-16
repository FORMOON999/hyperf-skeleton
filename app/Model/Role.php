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
 * @property string $name 角色名称
 * @property string $code 角色编码
 * @property int $sort 排序
 * @property int $status 状态
 */
class Role extends BaseModel
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
    protected ?string $table = 'role';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = ['id', 'created_at', 'updated_at', 'deleted_at', 'name', 'code', 'sort', 'status'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'sort' => 'integer', 'status' => 'integer'];
    /**
     * @return BaseModelEntity
     */
    public function newEntity() : BaseModelEntity
    {
        return new RoleEntity($this->toArray());
    }
}