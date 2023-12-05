<?php

declare(strict_types=1);

namespace App\Repository\Dao\MySQL;

use Hyperf\Database\Model\Builder;
use App\Common\BaseMySQLDao;
use App\Common\MySQLDaoTrait;
use App\Repository\Dao\Contract\AdminDaoInterface;
use App\Model\Admin;

class AdminDao extends BaseMySQLDao implements AdminDaoInterface
{
    use MySQLDaoTrait;

    public function modelClass(): string
    {
        return Admin::class;
    }

    protected function handleSearch(Builder $query, array $search, array $condition, array $sort): array
    {
        return [$query, $search, $condition, $sort];
    }
}
