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
