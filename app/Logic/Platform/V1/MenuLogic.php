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

use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\MenuError;
use App\Controller\Platform\V1\Menu\Request\MenuCreateRequest;
use App\Controller\Platform\V1\Menu\Request\MenuDetailRequest;
use App\Controller\Platform\V1\Menu\Request\MenuListRequest;
use App\Controller\Platform\V1\Menu\Request\MenuModifyRequest;
use App\Controller\Platform\V1\Menu\Request\MenuRemoveRequest;
use App\Controller\Platform\V1\Menu\Response\MenuDetailResponse;
use App\Controller\Platform\V1\Menu\Response\MenuListResponse;
use App\Infrastructure\MenuInterface;
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
}
