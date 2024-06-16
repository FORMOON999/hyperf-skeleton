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

use Hyperf\Context\ApplicationContext;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\Database\Query\Builder as QueryBuilder;
use Hyperf\ModelCache\CacheableInterface;
use Hyperf\ModelCache\InvalidCacheManager;

trait ModelCacheable
{
    protected bool $useCacheBuilder = false;

    protected array $cacheKeys = ['id'];

    protected bool $enableCacheAll = false;

    protected ?ModelCacheManager $manager = null;

    /**
     * Fetch a model from cache.
     * @param mixed $id
     * @return null|self
     */
    public static function findFromCache($id): ?Model
    {
        $container = ApplicationContext::getContainer();
        $manager = $container->get(ModelCacheManager::class);

        return $manager->findFromCache($id, static::class);
    }

    /**
     * Fetch models from cache.
     * @return Collection<int, self>
     */
    public static function findManyFromCache(array $ids): Collection
    {
        $container = ApplicationContext::getContainer();
        $manager = $container->get(ModelCacheManager::class);

        $ids = array_unique($ids);
        return $manager->findManyFromCache($ids, static::class);
    }

    /**
     * @param mixed $id
     * @return null|Model
     *
     * $this->findFromCacheByCustom('admin', function ($username) {
     *      return model::query()->where('username', $username])->first()
     * }, 'username');
     */
    public function findFromCacheByCustom($id, ?callable $call = null, string $customKey = 'id'): ?Model
    {
        return $this->getManager()->findFromCacheByCustom($id, get_called_class(), $call, $customKey);
    }

    /**
     * @return Collection
     *
     * $this->findFromCacheByCustom(['admin','admin2'], function ($username) {
     *      return model::query()->where('username', $username])->get()
     * }, 'username');
     */
    public function findManyFromCacheByCustom(array $ids, ?callable $call = null, string $customKey = 'id'): Collection
    {
        return $this->getManager()->findManyFromCacheByCustom($ids, get_called_class(), $call, $customKey);
    }

    public function findAllFromCache(?callable $call = null): Collection
    {
        return $this->getManager()->findAllFromCache(get_called_class(), $call);
    }

    /**
     * Delete model from cache.
     */
    public function deleteCache(): bool
    {
        $ids = [];
        foreach ($this->cacheKeys as $cacheKey) {
            $ids[$cacheKey][] = $this->getAttributeValue($cacheKey);
        }

        foreach ($ids as $k => $v) {
            $this->deleteCacheByCustom($v, $k);
        }

        if ($this->enableCacheAll) {
            $this->deleteCacheByAll();
        }
        // return $this->getManager()->destroy([$this->getKey()], get_called_class());
        return true;
    }

    public function deleteCacheByCustom(array $ids, string $key): bool
    {
        return $this->getManager()->destroyByCustom($ids, get_called_class(), $key);
    }

    public function deleteCacheByAll(): bool
    {
        return $this->getManager()->destroyByAll(get_called_class());
    }

    /**
     * Get the expired time for cache.
     */
    public function getCacheTTL(): ?int
    {
        return null;
    }

    /**
     * Increment a column's value by a given amount.
     * @param string $column
     * @param float|int $amount
     * @return int
     */
    public function increment($column, $amount = 1, array $extra = [])
    {
        $res = parent::increment($column, $amount, $extra);
        if ($res > 0) {
            if ($this->getConnection()->transactionLevel() && $this instanceof CacheableInterface) {
                InvalidCacheManager::instance()->push($this);
            } elseif (empty($extra)) {
                // Only increment a column's value.
                $this->getManager()->increment($this->getKey(), $column, $amount, get_called_class());
            } else {
                // Update other columns, when increment a column's value.
                $this->deleteCache();
            }
        }
        return $res;
    }

    /**
     * Decrement a column's value by a given amount.
     * @param string $column
     * @param float|int $amount
     * @return int
     */
    public function decrement($column, $amount = 1, array $extra = [])
    {
        $res = parent::decrement($column, $amount, $extra);
        if ($res > 0) {
            if ($this->getConnection()->transactionLevel() && $this instanceof CacheableInterface) {
                InvalidCacheManager::instance()->push($this);
            } elseif (empty($extra)) {
                // Only decrement a column's value.
                $this->getManager()->increment($this->getKey(), $column, -$amount, get_called_class());
            } else {
                // Update other columns, when decrement a column's value.
                $this->deleteCache();
            }
        }
        return $res;
    }

    /**
     * Create a new Model query builder for the model.
     * @param QueryBuilder $query
     */
    public function newModelBuilder($query): Builder
    {
        if ($this->useCacheBuilder) {
            $modelCacheBuilder = new ModelCacheBuilder($query);
            $modelCacheBuilder->enableCacheAll = $this->enableCacheAll;
            $modelCacheBuilder->cacheKeys = $this->cacheKeys;
            return $modelCacheBuilder;
        }

        return parent::newModelBuilder($query);
    }

    public function newQuery(bool $cache = true): Builder
    {
        $this->useCacheBuilder = $cache;
        return parent::newQuery();
    }

    /**
     * @param bool $cache Whether to delete the model cache when batch update
     * @return Builder|static
     */
    public static function query(bool $cache = true): Builder
    {
        return (new static())->newQuery($cache);
    }

    private function getManager(): ModelCacheManager
    {
        if (is_null($this->manager)) {
            $this->manager = \Hyperf\Support\make(ModelCacheManager::class);
        }
        return $this->manager;
    }
}
