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

namespace App\Common\Core;

use App\Common\Core\Cacheable\ModelCacheable;
use App\Common\Core\Entity\BaseModelEntity;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\CacheableInterface;

abstract class BaseModel extends Model implements CacheableInterface
{
    use SoftDeletes;
    use MySQLModelTrait;
    use ModelCacheable;

    abstract public function newEntity(): BaseModelEntity;
}
