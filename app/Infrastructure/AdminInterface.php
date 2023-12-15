<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Common\Core\Entity\Output;

interface AdminInterface
{

    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @param array $page 分页条件
     * @return Output
     */
    public function getList(array $condition, array $search, array $field = ['*'], array $sort = [], array $page = []): Output;

    /**
     * @param array $condition 控制参数
     * @param array $data 新增数据
     * @return int|string
     */
    public function create(array $condition, array $data): int|string;

    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $data 更新数据
     * @return int
     */
    public function modify(array $condition, array $search, array $data): int;

    /**
     * @param array $search
     * @param array $condition 控制参数
     * @return int
     */
    public function remove(array $condition, array $search): int;

    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $field 字段
     * @param array $sort 排序条件
     * @return mixed
     */
    public function detail(array $condition, array $search, array $field = ['*'], array $sort = []): mixed;
}
