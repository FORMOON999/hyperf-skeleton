<?php

declare(strict_types=1);

namespace App\Service;

use App\Infrastructure\RoleInterface;
use App\Model\Role;
use App\Model\RoleEntity;
use App\Common\Core\Entity\Output;

class RoleService implements RoleInterface
{

    public function __construct(protected Role $role)
    {

    }

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
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        return $this->role->buildQuery($search)->update($data);
    }

    public function remove(array $search): int
    {
        return $this->role->buildQuery($search)->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?RoleEntity
    {
        $query = $this->role->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }
}
