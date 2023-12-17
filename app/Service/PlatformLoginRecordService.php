<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Core\BaseService;
use App\Common\Exceptions\BusinessException;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use App\Infrastructure\PlatformLoginRecordInterface;
use App\Model\PlatformLoginRecord;
use App\Model\PlatformLoginRecordEntity;
use App\Common\Core\Entity\Output;

class PlatformLoginRecordService extends BaseService implements PlatformLoginRecordInterface
{

    public function __construct(protected platformLoginRecord $platformLoginRecord)
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
        $query = $this->platformLoginRecord->buildQuery($search, $sort)->with(...$withs)->select($field);
        return $this->platformLoginRecord->output($query, $page);
    }

    /**
     * @param array $data 新增数据
     * @return int|string
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
     * @return int
     */
    public function modify(array $search, array $data): int
    {
        return $this->platformLoginRecord->buildQuery($search)->update($data);
    }

    /**
     * @param array $search 搜索参数
     * @return int
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
     * @return PlatformLoginRecordEntity|null
     */
    public function detail(array $withs, array $search, array $field = ['*'], array $sort = []): ?PlatformLoginRecordEntity
    {
        /**
         * @var PlatformLoginRecordEntity
         */
        return $this->platformLoginRecord->buildQuery($search, $sort)->with(...$withs)->select($field)->first();
    }
}
