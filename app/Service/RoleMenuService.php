<?php

declare(strict_types=1);

namespace App\Service;

use App\Infrastructure\RoleMenuInterface;
use App\Model\RoleMenu;
use App\Model\RoleMenuEntity;
use App\Common\Core\Entity\Output;

class RoleMenuService implements RoleMenuInterface
{

    public function __construct(protected RoleMenu $roleMenu)
    {

    }

    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output
    {
        $query = $this->roleMenu->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $this->roleMenu->output($query, $page);
    }

    public function create(array $data): int|string
    {
        $model = clone $this->roleMenu;
        $data = $this->check($data);
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        $data = $this->check($data, $search);
        return $this->roleMenu->buildQuery($search)->update($data);
    }

    public function remove(array $search): ?bool
    {
        return $this->roleMenu->buildQuery($search)->first()?->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?RoleMenuEntity
    {
        $query = $this->roleMenu->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }

    protected function check(array $data, array $search = []): array
    {
        return $data;
    }
}
