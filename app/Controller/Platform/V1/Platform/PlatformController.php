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

namespace App\Controller\Platform\V1\Platform;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\PlatformMiddleware;
use App\Controller\Platform\V1\Platform\Request\PlatformCreateRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformDetailRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformListRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformModifyRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformRemoveRequest;
use App\Controller\Platform\V1\Platform\Response\PlatformDetailResponse;
use App\Controller\Platform\V1\Platform\Response\PlatformListResponse;
use App\Logic\Platform\V1\PlatformLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/platform')]
#[Api(tags: 'Platform/管理员管理')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class PlatformController extends BaseController
{
    #[Inject]
    protected PlatformLogic $platformLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取管理员列表')]
    public function getList(#[Valid] #[RequestBody] PlatformListRequest $request): PlatformListResponse
    {
        return $this->platformLogic->getList($request);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建管理员')]
    public function create(#[Valid] #[RequestBody] PlatformCreateRequest $request): BaseSuccessResponse
    {
        return $this->platformLogic->create($request);
    }

    #[PostMapping(path: 'modify/{id}')]
    #[ApiOperation('更新管理员')]
    public function modify(int $id, #[Valid] #[RequestBody] PlatformModifyRequest $request): BaseSuccessResponse
    {
        return $this->platformLogic->modify($id, $request);
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除管理员')]
    public function remove(#[Valid] #[RequestBody] PlatformRemoveRequest $request): BaseSuccessResponse
    {
        return $this->platformLogic->remove($request);
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理员详情')]
    public function detail(#[Valid] #[RequestBody] PlatformDetailRequest $request): PlatformDetailResponse
    {
        return $this->platformLogic->detail($request);
    }
}
