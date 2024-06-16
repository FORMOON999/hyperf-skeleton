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

namespace App\Controller\Admin\V1\Menu;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Common\Middleware\AdminMiddleware;
use App\Constants\Errors\MenuError;
use App\Controller\Admin\V1\Menu\Request\MenuCreateRequest;
use App\Controller\Admin\V1\Menu\Request\MenuDetailRequest;
use App\Controller\Admin\V1\Menu\Request\MenuListRequest;
use App\Controller\Admin\V1\Menu\Request\MenuModifyRequest;
use App\Controller\Admin\V1\Menu\Request\MenuRemoveRequest;
use App\Controller\Admin\V1\Menu\Response\MenuDetailResponse;
use App\Controller\Admin\V1\Menu\Response\MenuListResponse;
use App\Infrastructure\MenuInterface;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[Controller(prefix: 'api/v1/admin/menus')]
#[Api(tags: 'Admin/菜单管理管理')]
#[Middleware(AdminMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class MenuController extends BaseController
{
    #[Inject()]
    protected MenuInterface $menu;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取菜单管理列表')]
    public function getList(#[Valid] #[RequestBody] MenuListRequest $request): MenuListResponse
    {
        $result = $this->menu->getList(
            $request->getSearchParams(),
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
            $request->getSort(),
            $request->getPage(),
        );
        return new MenuListResponse($result);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建菜单管理')]
    public function create(#[Valid] #[RequestBody] MenuCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->menu->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(MenuError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新菜单管理')]
    public function modify(int $id, #[Valid] #[RequestBody] MenuModifyRequest $request): BaseSuccessResponse
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

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除菜单管理')]
    public function remove(#[Valid] #[RequestBody] MenuRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->menu->remove($request->toArray());
        if (! $result) {
            throw new BusinessException(MenuError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取菜单管理详情')]
    public function detail(#[Valid] #[RequestBody] MenuDetailRequest $request): MenuDetailResponse
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

    #[GetMapping(path: 'routes')]
    #[ApiOperation('路由列表')]
    public function routes(): PsrResponseInterface
    {
        $result = $this->menu->routes();
        return $this->response->success($result);
    }
}
