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

namespace App\Logic\Platform\V1;

use App\Common\Constants\SortType;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Core\Entity\Output;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\MenuError;
use App\Constants\Type\MenuType;
use App\Controller\Platform\V1\Menu\Request\MenuCreateRequest;
use App\Controller\Platform\V1\Menu\Request\MenuDetailRequest;
use App\Controller\Platform\V1\Menu\Request\MenuListRequest;
use App\Controller\Platform\V1\Menu\Request\MenuModifyRequest;
use App\Controller\Platform\V1\Menu\Request\MenuRemoveRequest;
use App\Controller\Platform\V1\Menu\Response\MenuDetailResponse;
use App\Controller\Platform\V1\Menu\Response\MenuListResponse;
use App\Controller\Platform\V1\Menu\Response\MenuRoutItem;
use App\Controller\Platform\V1\Menu\Response\MenuRoutMeta;
use App\Controller\Platform\V1\Menu\Response\MenuRoutResponse;
use App\Infrastructure\MenuInterface;
use App\Model\MenuEntity;
use Hyperf\Di\Annotation\Inject;

class MenuLogic
{
    #[Inject()]
    protected MenuInterface $menu;

    public function getList(MenuListRequest $request): MenuListResponse
    {
        $result = $this->menu->getList(
            $request->search?->setUnderlineName()?->toArray() ?? [],
            [
                'id',
                'created_at',
                'updated_at',
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
            [],
            $request->sort?->setUnderlineName()?->toArray() ?? [],
            $request->page?->toArray() ?? [],
        );
        return new MenuListResponse($result);
    }

    public function create(MenuCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->menu->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(MenuError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function modify(int $id, MenuModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->menu->modify(
            ['id' => $id],
            $request->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(MenuError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function remove(MenuRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->menu->remove($request->toArray());
        if (! $result) {
            throw new BusinessException(MenuError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function detail(MenuDetailRequest $request): MenuDetailResponse
    {
        $result = $this->menu->detail(
            $request->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
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
        );
        if (! $result) {
            throw new BusinessException(MenuError::NOT_FOUND());
        }
        return new MenuDetailResponse($result);
    }

    public function routes(): MenuRoutResponse
    {
        $response = new MenuRoutResponse();
        $tops = $this->getListByPid(MenuType::CATALOG(), 0);
        /**
         * @var MenuEntity $top
         */
        foreach ($tops->list as $top) {
            $meta = new MenuRoutMeta();
            $meta->title = $top->name;
            $meta->icon = $top->icon;
            $meta->roles = array_column($top->role, 'code');
            $meta->hidden = (bool) $top->status;

            $item = new MenuRoutItem();
            $item->path = $top->path;
            $item->component = $top->component;
            $item->redirect = $top->redirect;
            $item->meta = $meta;
            $item->children = $this->getChildren($top->id);
            $response->list[] = $item;
        }
        return $response;
    }

    protected function getListByPid(MenuType $type, int $pid): Output
    {
        return $this->menu->getList(
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
            ['sort' => SortType::ASC]
        );
    }

    protected function getChildren(int $pid)
    {
        $result = [];
        $menu = $this->getListByPid(MenuType::MENU(), $pid);
        /**
         * @var MenuEntity $data
         */
        foreach ($menu->list as $data) {
            $meta = new MenuRoutMeta();
            $meta->title = $data->name;
            $meta->icon = $data->icon;
            $meta->roles = array_column($data->role, 'code');
            $meta->hidden = (bool) $data->status;

            $item = new MenuRoutItem();
            $item->path = $data->path;
            $item->component = $data->component;
            $item->redirect = $data->redirect;
            $item->meta = $meta;
            $item->children = $this->getChildren($data->id);
            $result[] = $item;
        }

        return $result;
    }
}
