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
use App\Constants\Type\MenuType;
use App\Infrastructure\MenuInterface;
use App\Model\Menu;
use App\Model\MenuEntity;

class MenuService implements MenuInterface
{
    public function __construct(protected Menu $menu) {}

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
        $data = $this->check($data);
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        $data = $this->check($data, $search);
        return $this->menu->buildQuery($search)->update($data);
    }

    public function remove(array $search): ?bool
    {
        return $this->menu->buildQuery($search)->first()?->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?MenuEntity
    {
        $query = $this->menu->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }

    public function routes(): array
    {
        $response = [];
        $tops = $this->getListByPid(MenuType::CATALOG(), 0);
        /**
         * @var MenuEntity $top
         */
        foreach ($tops->list as $top) {
            $meta = [
                'title' => $top->name,
                'icon' => $top->icon,
                'roles' => array_column($top->role, 'code'),
                'hidden' => ! $top->status->getValue(),
            ];
            $item = [
                'path' => $top->path,
                'component' => $top->component,
                'redirect' => $top->redirect,
                'meta' => $meta,
                'children' => $this->getChildren($top->id),
            ];
            $response[] = $item;
        }
        return $response;
    }

    protected function getListByPid(MenuType $type, int $pid): Output
    {
        return $this->getList(
            ['pid' => $pid, 'type' => $type->getValue()],
            [
                'id',
                'pid',
                'name',
                'type',
                'path',
                'component',
                'perm',
                'sort',
                'status',
                'icon',
                'redirect',
            ],
            ['role'],
            ['sort' => 'asc']
        );
    }

    protected function getChildren(int $pid): array
    {
        $result = [];
        $menu = $this->getListByPid(MenuType::MENU(), $pid);
        /**
         * @var MenuEntity $data
         */
        foreach ($menu->list as $data) {
            $meta = [
                'title' => $data->name,
                'icon' => $data->icon,
                'roles' => array_column($data->role, 'code'),
                'hidden' => ! $data->status->getValue(),
            ];
            $item = [
                'path' => $data->path,
                'component' => $data->component,
                'redirect' => $data->redirect,
                'meta' => $meta,
                'children' => $this->getChildren($data->id),
            ];
            $result[] = $item;
        }
        return $result;
    }

    protected function check(array $data, array $search = []): array
    {
        return $data;
    }
}
