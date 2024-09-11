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

use App\Common\Core\Cacheable\ModelCacheBuilder;
use App\Common\Core\Entity\BaseModelEntity;
use App\Common\Core\Entity\Output;
use App\Common\Core\Entity\Page;
use App\Common\Helpers\Arrays\ArrayHelper;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;

trait MySQLModelTrait
{
    public function getModelByCondition(array $condition): static
    {
        $tableName = '';
        if (ArrayHelper::isValidValue($condition, '_subTable_date') && method_exists($this, 'getSubTableDate')) {
            $tableName = $this->getSubTableDate($condition['_subTable_date']);
        }
        if (ArrayHelper::isValidValue($condition, '_subTable_hash') && method_exists($this, 'getSubTableHash')) {
            $tableName = $this->getSubTableHash($condition['_subTable_hash']);
        }
        if (ArrayHelper::isValidValue($condition, '_subTable') && method_exists($this, 'getSubTable')) {
            $tableName = $this->getSubTable($condition['_subTable']);
        }
        if (ArrayHelper::isValidValue($condition, '_table')) {
            $tableName = $condition['_table'];
        }
        if ($tableName) {
            $this->setTable($tableName);
        }
        return $this;
    }

    public function buildQuery(array $search = [], array $sorts = []): Builder|ModelCacheBuilder
    {
        /**
         * @var ModelCacheBuilder $query
         */
        $query = $this->newQuery();
        // sort
        if (! empty($sorts)) {
            $query = $query->sorts($sorts);
        }

        if (empty($search)) {
            return $query;
        }
        return $query->whereSearch($search);
    }

    public function output(Builder $query, ?array $pages = []): Output
    {
        $output = new Output();
        if (! empty($pages)) {
            $page = new Page($pages);
            $pattern = '/^SELECT(.*)FROM/i';
            $replace = 'SELECT COUNT(*) AS count FROM';
            $sql = preg_replace($pattern, $replace, $query->toSql());
            //$sql = sprintf('select count(*) as count from (%s) as b', $query->toSql());
            $output->total = Db::selectOne($sql, $query->getBindings())->count;

            $query->forPage($page->page, $page->pageSize);
            $output->page = $page->page;
            $output->pageSize = $page->pageSize;
        }

        $output->list = $query->get()->map(function ($model) {
            return $model->newEntity();
        })->toArray();
        return $output;
    }

    public function getTable(bool $isTablePrefix = false): string
    {
        $tableName = parent::getTable();
        if ($isTablePrefix) {
            $tableName = $this->getConnection()->getTablePrefix() . $tableName;
        }
        return $tableName;
    }

    public function batchInsert(array $data): int
    {
        $data = $this->appendTime($data);
        $ret = $this->newQuery()->insert($data);
        return $ret ? 1 : 0;
    }

    public function batchUpdate(array $data, array $column = []): int
    {
        $data = $this->appendTime($data, [$this->getUpdatedAtColumn()]);
        if (empty($column)) {
            $column = array_keys(current($data));
        }
        return $this->newQuery()->upsert($data, [$this->getKeyName()], $column);
    }

    private function appendTime(array $data, array $columns = []): array
    {
        if (empty($columns)) {
            $columns = [$this->getCreatedAtColumn(), $this->getUpdatedAtColumn()];
        }

        $result = [];
        $time = $this->freshTimestamp();
        $now = $this->timestamps ? $this->fromDateTime($time) : $time->timestamp;
        foreach ($data as $item) {
            if ($item instanceof BaseModelEntity) {
                $item = $item->setUnderlineName()->toArray();
            }
            foreach ($columns as $column) {
                if (! ArrayHelper::keyExists($item, $column)) {
                    $item[$column] = $now;
                }
            }
            $result[] = $item;
        }
        return $result;
    }
}
