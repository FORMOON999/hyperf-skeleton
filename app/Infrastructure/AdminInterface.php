<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Common\Core\Entity\Output;
use App\Model\AdminEntity;

interface AdminInterface
{

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @param array $page 分页条件
     * @return Output
     */
    public function getList(array $withs, array $search, array $field = ['*'], array $sort = [], array $page = []): Output;

    /**
     * @param array $data 新增数据
     * @return int|string
     */
    public function create(array $data): int|string;

    /**
     * @param array $search 搜索参数
     * @param array $data 更新数据
     * @return int
     */
    public function modify(array $search, array $data): int;

    /**
     * @param array $search
     * @return int
     */
    public function remove(array $search): int;

    /**
     * @param array $withs 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @return null|AdminEntity
     */
    public function detail(array $withs, array $search, array $field = ['*'], array $sort = []): ?AdminEntity;
}
