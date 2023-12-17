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
 * @property int $platform_id 管理台id
 * @property string $ip ip
 * @property string $address 地址
 * @property string $address1 地址1
 * @property string $address2 地址2
 */
class PlatformLoginRecord extends BaseModel
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
    protected ?string $table = 'platform_login_record';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected array $fillable = ['id', 'created_at', 'updated_at', 'deleted_at', 'platform_id', 'ip', 'address', 'address1', 'address2'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected array $casts = ['id' => 'integer', 'platform_id' => 'integer'];
    /**
     * @return BaseModelEntity
     */
    public function newEntity() : BaseModelEntity
    {
        return new PlatformLoginRecordEntity($this->getAttributes());
    }
}