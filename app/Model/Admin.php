<?php

declare (strict_types=1);
namespace App\Model;

use App\Common\Core\BaseModel;
use App\Common\Core\Entity\BaseModelEntity;
/**
 * @property int $id 
 * @property string $username 账号
 * @property string $password 密码
 * @property int $status 昵称
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $deleted_at 删除时间
 */
class Admin extends BaseModel
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
    protected ?string $table = 'admin';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = ['id', 'username', 'password', 'status', 'created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'status' => 'integer'];
    /**
     * @return BaseModelEntity
     */
    public function newEntity() : BaseModelEntity
    {
        return new AdminEntity($this->getAttributes());
    }
}