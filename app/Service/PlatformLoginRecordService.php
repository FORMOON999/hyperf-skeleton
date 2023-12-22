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

use App\Common\Core\BaseService;
use App\Common\Core\Entity\Output;
use App\Infrastructure\PlatformLoginRecordInterface;
use App\Model\PlatformLoginRecord;
use App\Model\PlatformLoginRecordEntity;

class PlatformLoginRecordService extends BaseService implements PlatformLoginRecordInterface
{
    public function __construct(protected platformLoginRecord $platformLoginRecord) {}

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @param array $page 分页条件
     */
    public function getList(array $withs, array $search, array $field = ['*'], array $sort = [], array $page = []): Output
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

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     */
    public function detail(array $withs, array $search, array $field = ['*'], array $sort = []): ?PlatformLoginRecordEntity
    {
        $query = $this->platformLoginRecord->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }
}
