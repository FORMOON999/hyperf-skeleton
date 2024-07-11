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

namespace App\Common\Core\Cacheable;

use App\Common\Helpers\Arrays\ArrayHelper;
use Closure;
use Hyperf\Context\ApplicationContext;
use Hyperf\Database\Model\Builder as ModelBuilder;

class ModelCacheBuilder extends ModelBuilder
{
    public array $cacheKeys = [];

    public bool $enableCacheAll = false;

    public function delete()
    {
        return $this->deleteCache(function () {
            return parent::delete();
        });
    }

    public function update(array $values)
    {
        return $this->deleteCache(function () use ($values) {
            return parent::update($values);
        });
    }

    public function sorts(?array $sorts): ModelCacheBuilder
    {
        if (empty($sorts)) {
            return $this;
        }
        foreach ($sorts as $key => $value) {
            if (empty($value)) {
                continue;
            }
            $this->query->orderBy($key, $value);
        }

        return $this;
    }

    public function whereSearch(?array $search): ModelCacheBuilder
    {
        if (empty($search)) {
            return $this;
        }
        if (ArrayHelper::isIndexed($search)) {
            $this->query->where($search);
        } else {
            foreach ($search as $key => $value) {
                if (is_null($value)) {
                    continue;
                }
                is_array($value) ? $this->query->whereIn($key, $value) : $this->query->where($key, $value);
            }
        }
        return $this;
    }

    public function betweenTime(string $field, array &$data): ModelCacheBuilder
    {
        $startTime = ArrayHelper::remove($data, 'start_time', '');
        $endTime = ArrayHelper::remove($data, 'end_time', '');
        if ($startTime && $endTime) {
            $this->query->whereBetween($field, [$startTime, $endTime]);
            return $this;
        }

        if ($startTime) {
            $this->query->where($field, '>=', $startTime);
        }
        if ($endTime) {
            $this->query->where($field, '<', $endTime);
        }

        return $this;
    }

    public function whereLike(string $field, array &$data): ModelCacheBuilder
    {
        $value = ArrayHelper::remove($data, $field, '');
        if ($value) {
            $this->query->where($field, 'like', $value . '%');
        }
        return $this;
    }

    public function whereCondition(string $field, array &$data, string $operator = '='): ModelCacheBuilder
    {
        $value = ArrayHelper::remove($data, $field, '');
        if ($value) {
            $this->query->where($field, $operator, $value);
        }
        return $this;
    }

    protected function deleteCache(Closure $closure)
    {
        $manger = ApplicationContext::getContainer()->get(ModelCacheManager::class);
        if ($this->enableCacheAll) {
            $manger->destroyByAll(get_class($this->model));
        }
        $queryBuilder = clone $this;
        $ids = [];
        $models = $queryBuilder->get($this->cacheKeys);
        foreach ($models as $model) {
            foreach ($this->cacheKeys as $cacheKey) {
                $ids[$cacheKey][] = $model->{$cacheKey};
            }
        }
        if (empty($ids)) {
            return 0;
        }

        $result = $closure();
        foreach ($ids as $k => $v) {
            $manger->destroyByCustom($v, get_class($this->model), $k);
        }

        return $result;
    }
}
