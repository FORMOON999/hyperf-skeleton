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
use App\Infrastructure\AdminInterface;
use App\Model\Admin;
use App\Model\AdminEntity;

class AdminService extends BaseService implements AdminInterface
{
    public function __construct(protected Admin $admin) {}

    public function getList(array $withs, array $search, array $field = ['*'], array $sort = [], array $page = []): Output
    {
        $query = $this->admin->buildQuery($search, $sort)->with(...$withs)->select($field);
        return $this->admin->output($query, $page);
    }

    public function create(array $data): int|string
    {
        $model = clone $this->admin;
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
        return $this->admin->buildQuery($search)->update($data);
    }

    /**
     * @param array $search 搜索参数
     */
    public function remove(array $search): int
    {
        return $this->admin->buildQuery($search)->delete();
    }

    public function detail(array $withs, array $search, array $field = ['*'], array $sort = []): ?AdminEntity
    {
        /**
         * @var AdminEntity
         */
        return $this->admin->buildQuery($search, $sort)->with(...$withs)->select($field)->first();
    }
}
