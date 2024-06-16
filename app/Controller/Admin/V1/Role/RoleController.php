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

namespace App\Controller\Admin\V1\Role;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Common\Middleware\AdminMiddleware;
use App\Constants\Errors\RoleError;
use App\Controller\Admin\V1\Role\Request\RoleCreateRequest;
use App\Controller\Admin\V1\Role\Request\RoleDetailRequest;
use App\Controller\Admin\V1\Role\Request\RoleListRequest;
use App\Controller\Admin\V1\Role\Request\RoleModifyRequest;
use App\Controller\Admin\V1\Role\Request\RoleRemoveRequest;
use App\Controller\Admin\V1\Role\Response\RoleDetailResponse;
use App\Controller\Admin\V1\Role\Response\RoleListResponse;
use App\Infrastructure\RoleInterface;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/admin/role')]
#[Api(tags: 'Admin/角色管理管理')]
#[Middleware(AdminMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class RoleController extends BaseController
{
    #[Inject()]
    protected RoleInterface $role;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取角色管理列表')]
    public function getList(#[Valid] #[RequestBody] RoleListRequest $request): RoleListResponse
    {
        $result = $this->role->getList(
            $request->getSearchParams(),
            [
                'id',
                'created_at',
                'updated_at',
                'name',
                'code',
                'sort',
                'status',
            ],
            [],
            $request->getSort(),
            $request->getPage(),
        );
        return new RoleListResponse($result);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建角色管理')]
    public function create(#[Valid] #[RequestBody] RoleCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->role->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(RoleError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新角色管理')]
    public function modify(int $id, #[Valid] #[RequestBody] RoleModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->role->modify(
            ['id' => $id],
            $request->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(RoleError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除角色管理')]
    public function remove(#[Valid] #[RequestBody] RoleRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->role->remove($request->toArray());
        if (! $result) {
            throw new BusinessException(RoleError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取角色管理详情')]
    public function detail(#[Valid] #[RequestBody] RoleDetailRequest $request): RoleDetailResponse
    {
        $result = $this->role->detail(
            $request->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'name',
                'code',
                'sort',
                'status',
            ],
        );
        if (! $result) {
            throw new BusinessException(RoleError::NOT_FOUND());
        }
        return new RoleDetailResponse($result);
    }
}