<?php

declare(strict_types=1);

namespace App\Repository\Dao\Contract;

use Lengbin\Common\Entity\Page;

interface AdminDaoInterface
{
    /**
     * @param array $condition 控制参数
     * @param array $search 搜索参数
     * @param array $sort 排序参数
     * @param Page $page 分页参数
     * @param array $field 字段
     * @return array
     */
    public function getList(array $condition, array $search, array $sort, Page $page, array $field = ['*']): array;

    /**
     * @param array $condition 控制参数
     * @param array $data 新增数据
     * @return array
     */
    public function create(array $condition, array $data): array;

    /**
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
     * @param string[] $field 字段
     * @return array
     */
    public function detail(array $condition, array $search, array $field = ['*']): array;
}
