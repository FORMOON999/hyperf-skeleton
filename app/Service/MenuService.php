<?php

declare(strict_types=1);

namespace App\Service;

use App\Infrastructure\MenuInterface;
use App\Model\Menu;
use App\Model\MenuEntity;
use App\Common\Core\Entity\Output;

class MenuService implements MenuInterface
{

    public function __construct(protected Menu $menu)
    {

    }

    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output
    {
        $query = $this->menu->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $this->menu->output($query, $page);
    }

    public function create(array $data): int|string
    {
        $model = clone $this->menu;
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        return $this->menu->buildQuery($search)->update($data);
    }

    public function remove(array $search): int
    {
        return $this->menu->buildQuery($search)->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?MenuEntity
    {
        $query = $this->menu->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }
}
