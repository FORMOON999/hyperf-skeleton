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

use App\Common\Core\BaseModel;
use App\Common\Core\Entity\BaseModelEntity;
use Hyperf\Database\Model\Relations\HasOne;

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
     * primaryKey.
     */
    protected string $primaryKey = 'id';

    /**
     * The table associated with the model.
     */
    protected ?string $table = 'platform_login_record';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = ['id', 'created_at', 'updated_at', 'deleted_at', 'platform_id', 'ip', 'address', 'address1', 'address2'];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['id' => 'integer', 'platform_id' => 'integer'];

    public function newEntity(): BaseModelEntity
    {
        return new PlatformLoginRecordEntity($this->getAttributes());
    }

    public function platform(): HasOne
    {
        return $this->hasOne(Platform::class, 'id', 'platform_id')->select(['id', 'nickname']);
    }
}
