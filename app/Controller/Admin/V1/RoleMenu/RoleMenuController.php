<?php
declare(strict_types=1);

namespace App\Controller\Admin\V1\RoleMenu;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\AdminMiddleware;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\RoleMenuError;
use App\Infrastructure\RoleMenuInterface;
use App\Controller\Admin\V1\RoleMenu\Request\RoleMenuListRequest;
use App\Controller\Admin\V1\RoleMenu\Response\RoleMenuListResponse;
use App\Controller\Admin\V1\RoleMenu\Request\RoleMenuCreateRequest;
use App\Controller\Admin\V1\RoleMenu\Request\RoleMenuDetailRequest;
use App\Controller\Admin\V1\RoleMenu\Response\RoleMenuDetailResponse;
use App\Controller\Admin\V1\RoleMenu\Request\RoleMenuModifyRequest;
use App\Controller\Admin\V1\RoleMenu\Request\RoleMenuRemoveRequest;

#[Controller(prefix: 'api/v1/admin/role/menu')]
#[Api(tags: 'Admin/角色菜单关联管理')]
#[Middleware(AdminMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class RoleMenuController extends BaseController
{
    #[Inject()]
    protected RoleMenuInterface $roleMenu;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取角色菜单关联列表')]
    public function getList(#[Valid] #[RequestBody] RoleMenuListRequest $request): RoleMenuListResponse
    {
        $result = $this->roleMenu->getList(
            $request->getSearchParams(),
            [
    'id',
    'created_at',
    'updated_at',
    'role_id',
    'menu_id',
],
            [],
            $request->getSort(),
            $request->getPage(),
        );
        return new RoleMenuListResponse($result);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建角色菜单关联')]
    public function create(#[Valid] #[RequestBody] RoleMenuCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->roleMenu->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(RoleMenuError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新角色菜单关联')]
    public function modify(int $id, #[Valid] #[RequestBody] RoleMenuModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->roleMenu->modify(
            ['id' => $id],
            $request->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(RoleMenuError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除角色菜单关联')]
    public function remove(#[Valid] #[RequestBody] RoleMenuRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->roleMenu->remove($request->toArray());
        if (! $result) {
            throw new BusinessException(RoleMenuError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取角色菜单关联详情')]
    public function detail(#[Valid] #[RequestBody] RoleMenuDetailRequest $request): RoleMenuDetailResponse
    {
        $result = $this->roleMenu->detail(
            $request->toArray(),
            [
    'id',
    'created_at',
    'updated_at',
    'role_id',
    'menu_id',
],
        );
        if (! $result) {
            throw new BusinessException(RoleMenuError::NOT_FOUND());
        }
        return new RoleMenuDetailResponse($result);
    }
}