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
use App\Infrastructure\PlatformLoginRecordInterface;
use App\Model\PlatformLoginRecord;
use App\Model\PlatformLoginRecordEntity;

class PlatformLoginRecordService implements PlatformLoginRecordInterface
{
    public function __construct(protected platformLoginRecord $platformLoginRecord) {}

    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output
    {
        $query = $this->platformLoginRecord->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $this->platformLoginRecord->output($query, $page);
    }

    /**
     * @param array $data 新增数据
     */
    public function create(array $data): int|string
    {
        $model = clone $this->platformLoginRecord;
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
        return $this->platformLoginRecord->buildQuery($search)->update($data);
    }

    /**
     * @param array $search 搜索参数
     */
    public function remove(array $search): int
    {
        return $this->platformLoginRecord->buildQuery($search)->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?PlatformLoginRecordEntity
    {
        $query = $this->platformLoginRecord->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }
}
