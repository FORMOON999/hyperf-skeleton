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

use App\Common\Core\Entity\BaseCondition;
use App\Common\Core\Entity\BaseModelEntity;
use App\Common\Core\MySQLModelTrait;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model as BaseModel;

abstract class Model extends BaseModel
{
    use SoftDeletes;
    use MySQLModelTrait;

    public function createByCondition(BaseCondition $condition, array|BaseModelEntity $data): array|BaseModelEntity
    {
        if (empty($data)) {
            return [];
        }

        if ($condition->_insert) {
            return $this->getModelByCondition($condition)->batchInsert($data);
        }

        if ($condition->_update) {
            return $this->getModelByCondition($condition)->batchUpdate($data);
        }

        $isEntity = $data instanceof BaseModelEntity;
        $attributes = $isEntity ? $data->toArray() : $data;
        $model = $this->getModelByCondition($condition)->fill($attributes);
        $ret = $model->save();

        if (! $ret) {
            return [];
        }

        $result = $model->toArray();
        if ($isEntity) {
            $data->configure($data, $result);
            return $data;
        }

        return $result;
    }

    public function getModelByCondition(BaseCondition $condition): static
    {
        $tableName = '';
        if ($condition->_subTable_date && method_exists($this, 'getSubTableDate')) {
            $tableName = $this->getSubTableDate($condition->_subTable_date);
        }

        if ($condition->_subTable_hash && method_exists($this, 'getSubTableHash')) {
            $tableName = $this->getSubTableHash($condition->_subTable_hash);
        }
        if ($condition->_subTable && method_exists($this, 'getSubTable')) {
            $tableName = $this->getSubTable($condition->_subTable);
        }
        if ($condition->_table) {
            $tableName = $condition->_table;
        }
        if ($tableName) {
            $this->setTable($tableName);
        }
        return $this;
    }
}
