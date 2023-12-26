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

namespace App\Service;

use App\Common\Core\Entity\Output;
use App\Infrastructure\PlatformInterface;
use App\Model\Platform;
use App\Model\PlatformEntity;

class PlatformService implements PlatformInterface
{
    public function __construct(protected platform $platform) {}

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @param array $page 分页条件
     */
    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output
    {
        $query = $this->platform->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $this->platform->output($query, $page);
    }

    /**
     * @param array $data 新增数据
     */
    public function create(array $data): int|string
    {
        $model = clone $this->platform;
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    /**
     * @param array $search 搜索参数
     * @param array $data 数据
     */
    public function modify(array $search, array $data): int
    {
        return $this->platform->buildQuery($search)->update($data);
    }

    /**
     * @param array $search 搜索参数
     */
    public function remove(array $search): int
    {
        return $this->platform->buildQuery($search)->delete();
    }

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     */
    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?PlatformEntity
    {
        $query = $this->platform->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }
}
