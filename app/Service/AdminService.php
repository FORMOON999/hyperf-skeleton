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
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\AdminError;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;

class AdminService extends BaseService
{
    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $sort 排序参数
     * @param array $page 分页参数
     * @param array $field 字段
     */
    public function getList(array $condition, array $search, array $sort, array $page, array $field = ['*']): array
    {
        $result = $this->adminDao->getList($condition, $search, $sort, $page, $field);
        if (ArrayHelper::isValidValue($condition, '_format')) {
            $result['list'] = $this->toArray($result['list'], function ($data) use ($condition) {
                return $this->format($data, $condition);
            });
        }
        return $result;
    }

    /**
     * @param array $condition 控制参数
     * @param array $data 数据
     */
    public function validate(array $condition, array $data, array $search = []): array
    {
        return $data;
    }

    /**
     * @param array $data 添加数据
     * @param array $condition 控制参数
     */
    public function create(array $condition, array $data): array
    {
        $data = $this->validate($condition, $data);
        $result = $this->adminDao->create($condition, $data);
        if (!$result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::CREATE_ERROR());
        }
        if (ArrayHelper::isValidValue($condition, '_format')) {
            return $this->format($result, $condition);
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
        $result = $this->adminDao->modify($condition, $search, $data);

        if (!$result && ArrayHelper::isValidValue($condition, '_throw')) {
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
        $result = $this->adminDao->remove($condition, $search);
        if (!$result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::DELETE_ERROR());
        }
        return $result;
    }

    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     */
    public function detail(array $condition, array $search, array $field = ['*']): array
    {
        $result = $this->adminDao->detail($condition, $search, $field);
        if (!$result && ArrayHelper::isValidValue($condition, '_throw')) {
            throw new BusinessException(AdminError::NOT_FOUND());
        }
        if (ArrayHelper::isValidValue($condition, '_format')) {
            return $this->format($result, $condition);
        }
        return $result;
    }

    /**
     * @param array $result 数据
     * @param array $condition 控制参数
     */
    public function format(array $result, array $condition): array
    {
        return $result;
    }
}
