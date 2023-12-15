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
use App\Common\Core\Entity\BaseModelEntity;
use App\Common\Core\Entity\Output;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\AdminError;
use App\Infrastructure\AdminInterface;
use App\Model\Admin;
use App\Model\AdminEntity;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;

class AdminService extends BaseService implements AdminInterface
{
    public function __construct(protected Admin $admin) {}

    public function getList(array $condition, array $search, array $field = ['*'], array $sort = [], array $page = []): Output
    {
        $query = $this->admin->buildQuery($search, $sort)->select($field);
        // todo 其他自己实现

        return $this->admin->output($query, $page);
    }

    public function create(array $condition, array $data): int|string
    {
        $model = clone $this->admin;
        $model->fill($data);
        $ret = $model->save();
        $result = $ret ? $model->getKey() : 0;

        if (! $result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::CREATE_ERROR());
        }
        return $result;
    }

    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $data 数据
     */
    public function modify(array $condition, array $search, array $data): int
    {
        $data = $this->validate($condition, $data, $search);
        $result = $this->admin->buildQuery($search)->update($data);
        if (! $result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::UPDATE_ERROR());
        }
        return $result;
    }

    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     */
    public function remove(array $condition, array $search): int
    {
        $query = $this->admin->buildQuery($search);
        $result = ArrayHelper::isValidValue($condition, '_delete') ? $query->forceDelete() : $query->delete();
        if (! $result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::DELETE_ERROR());
        }
        return $result;
    }

    public function detail(array $condition, array $search, array $field = ['*'], array $sort = []): ?AdminEntity
    {
        $query = $this->admin->buildQuery($search, $sort)->select($field);
        // todo 其他自己实现
        /**
         * @var AdminEntity $result
         */
        $result = $query->first();
        if (! $result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::NOT_FOUND());
        }
        return $result;
    }
}
