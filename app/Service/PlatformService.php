<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Core\BaseService;
use App\Common\Exceptions\BusinessException;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use App\Infrastructure\PlatformInterface;
use App\Model\Platform;
use App\Model\PlatformEntity;
use App\Common\Core\Entity\Output;

class PlatformService extends BaseService implements PlatformInterface
{

    public function __construct(protected platform $platform)
    {

    }

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @param array $page 分页条件
     * @return Output
     */
    public function getList(array $withs, array $search, array $field = ['*'], array $sort = [], array $page = []): Output
    {
        $query = $this->platform->buildQuery($search, $sort)->with(...$withs)->select($field);
        return $this->platform->output($query, $page);
    }

    /**
     * @param array $data 新增数据
     * @return int|string
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
     * @return int
     */
    public function modify(array $search, array $data): int
    {
        return $this->platform->buildQuery($search)->update($data);
    }

    /**
     * @param array $search 搜索参数
     * @return int
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
     * @return PlatformEntity|null
     */
    public function detail(array $withs, array $search, array $field = ['*'], array $sort = []): ?PlatformEntity
    {
        /**
         * @var PlatformEntity
         */
        return $this->platform->buildQuery($search, $sort)->with(...$withs)->select($field)->first();
    }
}
