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

namespace App\Controller\Platform\V1\Menu;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\PlatformMiddleware;
use App\Controller\Platform\V1\Menu\Request\MenuCreateRequest;
use App\Controller\Platform\V1\Menu\Request\MenuDetailRequest;
use App\Controller\Platform\V1\Menu\Request\MenuListRequest;
use App\Controller\Platform\V1\Menu\Request\MenuModifyRequest;
use App\Controller\Platform\V1\Menu\Request\MenuRemoveRequest;
use App\Controller\Platform\V1\Menu\Response\MenuDetailResponse;
use App\Controller\Platform\V1\Menu\Response\MenuListResponse;
use App\Logic\Platform\V1\MenuLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/menu')]
#[Api(tags: 'Platform/菜单管理管理')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class MenuController extends BaseController
{
    #[Inject]
    protected MenuLogic $menuLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取菜单管理列表')]
    public function getList(#[Valid] #[RequestBody] MenuListRequest $request): MenuListResponse
    {
        return $this->menuLogic->getList($request);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建菜单管理')]
    public function create(#[Valid] #[RequestBody] MenuCreateRequest $request): BaseSuccessResponse
    {
        return $this->menuLogic->create($request);
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新菜单管理')]
    public function modify(int $id, #[Valid] #[RequestBody] MenuModifyRequest $request): BaseSuccessResponse
    {
        return $this->menuLogic->modify($id, $request);
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除菜单管理')]
    public function remove(#[Valid] #[RequestBody] MenuRemoveRequest $request): BaseSuccessResponse
    {
        return $this->menuLogic->remove($request);
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取菜单管理详情')]
    public function detail(#[Valid] #[RequestBody] MenuDetailRequest $request): MenuDetailResponse
    {
        return $this->menuLogic->detail($request);
    }
}
