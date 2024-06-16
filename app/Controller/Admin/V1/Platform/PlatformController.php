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

namespace App\Controller\Admin\V1\Platform;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Common\Middleware\AdminMiddleware;
use App\Constants\Errors\PlatformError;
use App\Controller\Admin\V1\Platform\Request\PlatformCreateRequest;
use App\Controller\Admin\V1\Platform\Request\PlatformDetailRequest;
use App\Controller\Admin\V1\Platform\Request\PlatformListRequest;
use App\Controller\Admin\V1\Platform\Request\PlatformModifyRequest;
use App\Controller\Admin\V1\Platform\Request\PlatformRemoveRequest;
use App\Controller\Admin\V1\Platform\Response\MeResponse;
use App\Controller\Admin\V1\Platform\Response\PlatformDetailResponse;
use App\Controller\Admin\V1\Platform\Response\PlatformListResponse;
use App\Infrastructure\PlatformInterface;
use App\Infrastructure\RoleInterface;
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

#[Controller(prefix: 'api/v1/admin/platform')]
#[Api(tags: 'Admin/管理员管理')]
#[Middleware(AdminMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class PlatformController extends BaseController
{
    #[Inject()]
    protected PlatformInterface $platform;

    #[Inject]
    protected RoleInterface $role;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取管理员列表')]
    public function getList(#[Valid] #[RequestBody] PlatformListRequest $request): PlatformListResponse
    {
        $result = $this->platform->getList(
            $request->getSearchParams(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'password',
                'roles',
                'status',
                'last_time',
                'avatar',
            ],
            [],
            $request->getSort(),
            $request->getPage(),
        );
        return new PlatformListResponse($result);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建管理员')]
    public function create(#[Valid] #[RequestBody] PlatformCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新管理员')]
    public function modify(int $id, #[Valid] #[RequestBody] PlatformModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->modify(
            ['id' => $id],
            $request->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(PlatformError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除管理员')]
    public function remove(#[Valid] #[RequestBody] PlatformRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->remove($request->toArray());
        if (! $result) {
            throw new BusinessException(PlatformError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理员详情')]
    public function detail(#[Valid] #[RequestBody] PlatformDetailRequest $request): PlatformDetailResponse
    {
        $result = $this->platform->detail(
            $request->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'password',
                'roles',
                'avatar',
                'status',
                'last_time',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        return new PlatformDetailResponse($result);
    }

    #[GetMapping(path: 'me')]
    #[ApiOperation('获取当前登录用户信息')]
    public function me(): MeResponse
    {
        $result = $this->platform->detail(
            ['id' => $this->request->getAttribute('id')],
            [
                'id',
                'username',
                'nickname',
                'roles',
                'avatar',
            ],
        );
        return new MeResponse($result);
    }
}
