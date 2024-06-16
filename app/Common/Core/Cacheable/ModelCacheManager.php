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

use App\Common\Core\ArrayableInterface;
use DateInterval;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\ModelCache\CacheableInterface;
use Hyperf\ModelCache\Config;
use Hyperf\ModelCache\Handler\DefaultValueInterface;
use Hyperf\ModelCache\Handler\HandlerInterface;
use Hyperf\ModelCache\Handler\RedisStringHandler;
use Hyperf\ModelCache\Manager;
use InvalidArgumentException;

class ModelCacheManager extends Manager
{
    protected ?RedisStringHandler $redisStringHandler = null;

    public function findFromCacheByCustom($id, string $class, ?callable $call = null, string $customKey = 'id'): ?Model
    {
        /** @var Model $instance */
        $instance = new $class();
        $name = $instance->getConnectionName();

        if ($handler = $this->handlers[$name] ?? null) {
            $key = $this->getCacheKeyByCustom($id, $instance, $handler->getConfig(), $customKey);
            $data = $handler->get($key);
            if ($data) {
                return $instance->newFromBuilder(
                    $this->getAttributes($handler->getConfig(), $instance, $data)
                );
            }

            // Fetch it from database, because it not exists in cache handler.
            if ($data === null) {
                $model = $this->getModelByCustom($id, $customKey, $instance, $call);
                if ($model) {
                    [$model, $value] = $this->handleModel($instance, $handler, $model);
                    $ttl = $this->getCacheTTL($instance, $handler);
                    $handler->set($key, $value, $ttl);
                } else {
                    $ttl = $handler->getConfig()->getEmptyModelTtl();
                    $handler->set($key, $this->defaultValue($handler, $id), $ttl);
                }
                return $model;
            }

            // It not exists in cache handler and database.
            return null;
        }

        $this->logger->alert('Cache handler not exist, fetch data from call.');
        [$model] = $this->handleModel($instance, $handler, $this->getModelByCustom($id, $customKey, $instance, $call));
        return $model;
    }

    public function findManyFromCacheByCustom(array $ids, string $class, ?callable $call = null, string $customKey = 'id'): Collection
    {
        if (count($ids) === 0) {
            return new Collection([]);
        }

        /** @var Model $instance */
        $instance = new $class();

        $name = $instance->getConnectionName();

        if ($handler = $this->handlers[$name] ?? null) {
            $keys = [];
            foreach ($ids as $id) {
                $keys[] = $this->getCacheKeyByCustom($id, $instance, $handler->getConfig(), $customKey);
            }
            $data = $handler->getMultiple($keys);
            $items = [];
            $fetchIds = [];
            foreach ($data as $item) {
                if ($handler instanceof DefaultValueInterface && $handler->isDefaultValue($item)) {
                    $fetchIds[] = $handler->getPrimaryValue($item);
                    continue;
                }

                if (isset($item[$customKey])) {
                    if ($handler instanceof DefaultValueInterface) {
                        $item = $handler->clearDefaultValue($item);
                    }
                    $items[] = $item;
                    $fetchIds[] = $item[$customKey];
                }
            }
            // Get ids that not exist in cache handler.
            $targetIds = array_diff($ids, $fetchIds);
            if ($targetIds) {
                [$models, $dictionary] = $this->getDictionary($targetIds, $customKey, $instance, $handler, $call);
                $ttl = $this->getCacheTTL($instance, $handler);
                $emptyTtl = $handler->getConfig()->getEmptyModelTtl();
                foreach ($targetIds as $id) {
                    $key = $this->getCacheKeyByCustom($id, $instance, $handler->getConfig(), $customKey);
                    if ($model = $dictionary[$id] ?? null) {
                        $handler->set($key, $this->formatModel($model), $ttl);
                    } else {
                        $handler->set($key, $this->defaultValue($handler, $id), $emptyTtl);
                    }
                }

                $items = array_merge($items, $this->formatModels($models));
            }
            $map = [];
            foreach ($items as $item) {
                $map[$item[$customKey]] = $this->getAttributes($handler->getConfig(), $instance, $item);
            }

            $result = [];
            foreach ($ids as $id) {
                if (isset($map[$id])) {
                    $result[] = $map[$id];
                }
            }

            return $instance->hydrate($result);
        }

        $this->logger->alert('Cache handler not exist, fetch data from call.');
        // @phpstan-ignore-next-line
        $callData = call_user_func($call, $ids);
        if (is_array($callData)) {
            $callData = new Collection($callData);
        }
        return $callData;
    }

    public function destroyByCustom(iterable $ids, string $class, string $key): bool
    {
        /** @var Model $instance */
        $instance = new $class();

        $name = $instance->getConnectionName();
        if ($handler = $this->handlers[$name] ?? null) {
            $keys = [];
            foreach ($ids as $id) {
                $keys[] = $this->getCacheKeyByCustom($id, $instance, $handler->getConfig(), $key);
            }

            return $handler->deleteMultiple($keys);
        }

        return false;
    }

    public function findAllFromCache(string $class, ?callable $call = null): Collection
    {
        /** @var Model $instance */
        $instance = new $class();
        $name = $instance->getConnectionName();

        if ($handler = $this->handlers[$name] ?? null) {
            $this->cacheAll = true;
            $key = $this->getCacheKeyByAll($instance, $handler->getConfig());
            $stringHandler = $this->getRedisStringHandler($handler->getConfig());
            $data = $stringHandler->get($key);
            if ($data) {
                return $instance->hydrate($data);
            }

            // Fetch it from database, because it not exists in cache handler.
            if ($data === null) {
                $data = $this->getModelByAll($instance, $call);
                if ($data) {
                    $ttl = $this->getCacheTTL($instance, $handler);
                    $stringHandler->set($key, $data, $ttl);
                } else {
                    $ttl = $handler->getConfig()->getEmptyModelTtl();
                    $stringHandler->set($key, $data, $ttl);
                }
            }
            return $instance->hydrate($data);
        }

        $this->logger->alert('Cache handler not exist, fetch data from call.');
        return $instance->hydrate($this->getModelByAll($instance, $call));
    }

    public function destroyByAll(string $class): bool
    {
        /** @var Model $instance */
        $instance = new $class();

        $name = $instance->getConnectionName();
        if ($handler = $this->handlers[$name] ?? null) {
            $key = $this->getCacheKeyByAll($instance, $handler->getConfig());
            return $handler->deleteMultiple([$key]);
        }

        return false;
    }

    protected function getCacheTTL(Model $instance, HandlerInterface $handler): DateInterval|int
    {
        $ttl = parent::getCacheTTL($instance, $handler);
        if (is_int($ttl)) {
            $ttl += rand(1, 300);
        }
        return $ttl;
    }

    protected function getRedisStringHandler(Config $config): RedisStringHandler
    {
        if (is_null($this->redisStringHandler)) {
            $this->redisStringHandler = \Hyperf\Support\make(RedisStringHandler::class, ['config' => $config]);
        }
        return $this->redisStringHandler;
    }

    protected function getModelByCustom($id, string $key, Model $instance, ?callable $call = null)
    {
        if (! is_null($call)) {
            return call_user_func($call, $id);
        }

        return $instance->newQuery()->where($key, '=', $id)->first();
    }

    protected function getDictionary(array $targetIds, string $key, Model $instance, HandlerInterface $handler, ?callable $call = null)
    {
        if (is_null($call)) {
            $models = $instance->newQuery()->whereIn($key, $targetIds)->get();
            $dictionary = $models->getDictionary();
            return [$models, $dictionary];
        }

        $items = call_user_func($call, $targetIds);
        if ($items instanceof Collection) {
            $items = $items->all();
        }

        $models = $dictionary = [];
        foreach ($items as $value) {
            [$model, $data] = $this->handleModel($instance, $handler, $value);
            $models[] = $model;
            $dictionary[$data[$key]] = $model;
        }

        return [$models, $dictionary];
    }

    protected function handleModel(Model $instance, HandlerInterface $handler, mixed $result): array
    {
        switch (true) {
            case $result instanceof Model:
                $model = $result;
                $data = $this->formatModel($result);
                break;
            case $result instanceof ArrayableInterface:
                $data = $result->toArray();
                $model = $instance->newFromBuilder(
                    $this->getAttributes($handler->getConfig(), $instance, $data)
                );
                break;
            case is_array($result):
                $data = $result;
                $model = $instance->newFromBuilder(
                    $this->getAttributes($handler->getConfig(), $instance, $data)
                );
                break;
            default:
                throw new InvalidArgumentException('call data is not match!');
        }
        return [$model, $data];
    }

    protected function getCacheKeyByCustom($id, Model $model, Config $config, string $key): string
    {
        return sprintf(
            $config->getCacheKey(),
            $config->getPrefix(),
            $model->getTable(),
            $key,
            $id
        );
    }

    protected function getCacheKeyByAll(Model $model, Config $config): string
    {
        return sprintf(
            $config->getCacheKey(),
            $config->getPrefix(),
            $model->getTable(),
            '-',
            'all'
        );
    }

    protected function getModelByAll(Model $instance, ?callable $call = null)
    {
        $value = [];
        if (is_null($call)) {
            return $instance->newQuery()->get()->toArray();
        }

        $models = call_user_func($call);
        if (empty($models)) {
            return $value;
        }
        foreach ($models as $model) {
            switch (true) {
                case $model instanceof Model:
                    $value[] = $this->formatModel($model);
                    break;
                case $model instanceof ArrayableInterface:
                    $value[] = $model->toArray();
                    break;
                case is_array($model):
                    $value[] = $model;
                    break;
                default:
                    throw new InvalidArgumentException('call data is not match!');
            }
        }
        return $value;
    }
}
