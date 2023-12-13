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

use App\Common\Core\Entity\BaseModelEntity;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;

abstract class BaseModel extends Model
{
    use SoftDeletes;
    use MySQLModelTrait;

    abstract public function newEntity(): BaseModelEntity;

    public function newCollection(array $models = [])
    {
        $result = [];
        foreach ($models as $model) {
            if ($model instanceof BaseModelEntity) {
                $result = $models;
                break;
            }
            $result[] = $model->newEntity();
        }
        return parent::newCollection($result);
    }
}
