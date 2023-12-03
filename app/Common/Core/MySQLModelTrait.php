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
use App\Common\Core\Entity\OutputResult;
use Hyperf\Collection\Arr;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Query\Expression;
use Hyperf\Database\Query\Grammars\Grammar;
use Hyperf\DbConnection\Db;
use Lengbin\Common\Entity\Page;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;

trait MySQLModelTrait
{
    /**
     * insert or update a record.
     */
    public function insertOrUpdate(array $values, array $column = []): bool
    {
        if (empty($column)) {
            $column = array_keys(current($values));
        }
        $value = [];
        foreach ($column as $key => $item) {
            if (is_int($key)) {
                $value[$item] = Db::raw("values(`{$item}`)");
            } else {
                $value[$key] = $item instanceof Expression ? $item : Db::raw($item);
            }
        }
        $connection = $this->getConnection();   // 数据库连接
        $builder = $this->newQuery()->getQuery();   // 查询构造器
        $grammar = $builder->getGrammar();  // 语法器
        // 编译插入语句
        $insert = $grammar->compileInsert($builder, $values);
        // 编译重复后更新列语句。
        $update = $this->compileUpdateColumns($grammar, $value);
        // 构造查询语句
        $query = $insert . ' on duplicate key update ' . $update;
        // 组装sql绑定参数
        $bindings = $this->prepareBindingsForInsertOrUpdate($values, $value);
        // 执行数据库查询
        return $connection->insert($query, $bindings);
    }

    public function getTable(bool $isTablePrefix = false): string
    {
        $tableName = parent::getTable();
        if ($isTablePrefix) {
            $tableName = $this->getConnection()->getTablePrefix() . $tableName;
        }
        return $tableName;
    }

    /**
     * @param array|BaseModelEntity[] $data
     */
    public function batchInsert(array $data): array
    {
        $data = $this->appendTime($data);
        $ret = $this->newQuery()->insert($data);
        if (! $ret) {
            return [];
        }
        return $data;
    }

    /**
     * @param array|BaseModelEntity[] $data
     */
    public function batchUpdate(array $data, array $column = []): array
    {
        $data = $this->appendTime($data, [$this->getUpdatedAtColumn()]);
        $ret = $this->insertOrUpdate($data, $column);
        if (! $ret) {
            return [];
        }
        return $data;
    }

    /**
     * Compile all of the columns for an update statement.
     */
    private function compileUpdateColumns(Grammar $grammar, array $values): string
    {
        return \Hyperf\Collection\collect($values)->map(function ($value, $key) use ($grammar) {
            return $grammar->wrap($key) . ' = ' . $grammar->parameter($value);
        })->implode(', ');
    }

    /**
     * Prepare the bindings for an insert or update statement.
     */
    private function prepareBindingsForInsertOrUpdate(array $values, array $value): array
    {
        // Merge array of bindings
        $bindings = array_merge_recursive($values, [$value]);
        // Remove all of the expressions from a list of bindings.
        return array_values(array_filter(Arr::flatten($bindings, 1), function ($binding) {
            return ! $binding instanceof Expression;
        }));
    }

    /**
     * @param array|BaseModelEntity[] $data
     */
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
                $item = $time->toArray();
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
