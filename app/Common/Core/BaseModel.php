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

use App\Common\Core\Entity\BaseCondition;
use App\Common\Core\Entity\BaseModelEntity;
use App\Common\Core\Entity\BaseSort;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Db;
use Hyperf\DbConnection\Model\Model;
use Lengbin\Common\BaseObject;
use Lengbin\Common\Entity\Page;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;

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

    public function buildQuery(BaseCondition $condition, null|array|BaseObject $search = null, null|array|BaseSort $sorts = null): Builder
    {
        $query = $this->getModelByCondition($condition)->newQuery();
        if ($condition->_forUpdate) {
            $query = $query->lockForUpdate();
        }

        // sort
        if (! empty($sorts)) {
            $orderBys = $sorts instanceof BaseSort ? $sorts->setUnderlineName()->toArray() : $sorts;
            foreach ($orderBys as $key => $value) {
                if (empty($value)) {
                    continue;
                }
                $query = $query->orderBy($key, $value);
            }
        }

        $attributes = $search instanceof BaseObject ? $search->setUnderlineName()->toArray() : $search;
        if (empty($attributes)) {
            return $query;
        }

        if (ArrayHelper::isIndexed($attributes)) {
            return $query->where($attributes);
        }

        foreach ($attributes as $key => $value) {
            if ($condition->_exceptPk && $key == $this->getKeyName()) {
                $query = is_array($value) ? $query->whereNotIn($key, $value) : $query->where($key, '!=', $value);
                continue;
            }
            $query = is_array($value) ? $query->whereIn($key, $value) : $query->where($key, $value);
        }
        return $query;
    }

    public function createByCondition(BaseCondition $condition, array|BaseModelEntity $data): array|BaseModelEntity
    {
        if (empty($data)) {
            return [];
        }

        $model = $this->getModelByCondition($condition);
        if ($condition->_insert) {
            return $model->batchInsert($data);
        }

        if ($condition->_update) {
            return $model->batchUpdate($data);
        }

        $attributes = $data instanceof BaseModelEntity ? $data->setUnderlineName()->toArray() : $data;
        $model->fill($attributes);
        $ret = $model->save();

        if (! $ret) {
            return [];
        }
        return $model->newEntity();
    }

    public function modifyByCondition(BaseCondition $condition, array|BaseObject $search, array|BaseModelEntity $data): int
    {
        $query = $this->buildQuery($condition, $search);
        $attributes = $data instanceof BaseObject ? $data->setUnderlineName()->toArray() : $data;
        return $query->update($attributes);
    }

    public function removeByCondition(BaseCondition $condition, array|BaseObject $search): int
    {
        $query = $this->buildQuery($condition, $search);
        if ($condition->_delete) {
            return $query->forceDelete();
        }
        return $query->delete();
    }

    public function output(Builder $query, Page $page): array
    {
        $output = [];
        if ($page->total) {
            $sql = sprintf('select count(*) as count from (%s) as b', $query->toSql());
            $output['total'] = Db::selectOne($sql, $query->getBindings())->count;
        }

        if (! $page->all) {
            $query->forPage($page->page, $page->pageSize);
            $output['page'] = $page->page;
            $output['pageSize'] = $page->pageSize;
        }

        $output['list'] = $query->get()->toArray();
        return $output;
    }

    /**
     * @param array $data [start, end]
     */
    public function betweenTime(Builder $query, string $field, array $data): Builder
    {
        $query->where(function (Builder $builder) use ($field, $data) {
            if ($data['start'] > 0) {
                $builder->where($field, '>=', $data['start']);
            }
            if ($data['end'] > 0) {
                $builder->where($field, '<', $data['end']);
            }
        });

        return $query;
    }
}
