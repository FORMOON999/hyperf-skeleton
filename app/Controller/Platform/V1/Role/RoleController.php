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

namespace App\Controller\Platform\V1\Role;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\PlatformMiddleware;
use App\Controller\Platform\V1\Role\Request\RoleCreateRequest;
use App\Controller\Platform\V1\Role\Request\RoleDetailRequest;
use App\Controller\Platform\V1\Role\Request\RoleListRequest;
use App\Controller\Platform\V1\Role\Request\RoleModifyRequest;
use App\Controller\Platform\V1\Role\Request\RoleRemoveRequest;
use App\Controller\Platform\V1\Role\Response\RoleDetailResponse;
use App\Controller\Platform\V1\Role\Response\RoleListResponse;
use App\Logic\Platform\V1\RoleLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/role')]
#[Api(tags: 'Platform/角色管理管理')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class RoleController extends BaseController
{
    #[Inject]
    protected RoleLogic $roleLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取角色管理列表')]
    public function getList(#[Valid] #[RequestBody] RoleListRequest $request): RoleListResponse
    {
        return $this->roleLogic->getList($request);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建角色管理')]
    public function create(#[Valid] #[RequestBody] RoleCreateRequest $request): BaseSuccessResponse
    {
        return $this->roleLogic->create($request);
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新角色管理')]
    public function modify(int $id, #[Valid] #[RequestBody] RoleModifyRequest $request): BaseSuccessResponse
    {
        return $this->roleLogic->modify($id, $request);
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除角色管理')]
    public function remove(#[Valid] #[RequestBody] RoleRemoveRequest $request): BaseSuccessResponse
    {
        return $this->roleLogic->remove($request);
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取角色管理详情')]
    public function detail(#[Valid] #[RequestBody] RoleDetailRequest $request): RoleDetailResponse
    {
        return $this->roleLogic->detail($request);
    }
}
