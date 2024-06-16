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

use App\Common\Core\Entity\Output;
use App\Infrastructure\RoleInterface;
use App\Model\Role;
use App\Model\RoleEntity;
use Hyperf\DbConnection\Db;

class RoleService implements RoleInterface
{
    public function __construct(protected Role $role) {}

    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output
    {
        $query = $this->role->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $this->role->output($query, $page);
    }

    public function create(array $data): int|string
    {
        $model = clone $this->role;
        $data = $this->check($data);
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        $data = $this->check($data, $search);
        return $this->role->buildQuery($search)->update($data);
    }

    public function remove(array $search): ?bool
    {
        return $this->role->buildQuery($search)->first()?->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?RoleEntity
    {
        $query = $this->role->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }

    public function getPermissionByRoles(array $roles): array
    {
        return $this->role->buildQuery()
            ->leftJoin('role_menu', 'role_menu.role_id', '=', 'role.id')
            ->leftJoin('menu', 'menu.id', '=', 'role_menu.menu_id')
            ->where('menu.perm', '!=', '')
            ->whereIn('role.code', $roles)
            ->pluck('menu.perm')->toArray();
    }

    protected function check(array $data, array $search = []): array
    {
        return $data;
    }
}
