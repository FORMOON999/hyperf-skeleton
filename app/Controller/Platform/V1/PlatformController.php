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

namespace App\Controller\Platform\V1;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\PlatformMiddleware;
use App\Entity\Request\Platform\V1\Platform\PlatformCreateRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformDetailRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformListRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformModifyRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformRemoveRequest;
use App\Entity\Response\Platform\V1\Platform\PlatformDetailResponse;
use App\Entity\Response\Platform\V1\Platform\PlatformListResponse;
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
#[Api(tags: 'Platform/管理台/管理台管理')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class PlatformController extends BaseController
{
    #[Inject]
    protected PlatformLogic $platformLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取管理台列表')]
    public function getList(#[Valid] #[RequestBody] PlatformListRequest $request): PlatformListResponse
    {
        return $this->platformLogic->getList($request);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建管理台')]
    public function create(#[Valid] #[RequestBody] PlatformCreateRequest $request): BaseSuccessResponse
    {
        return $this->platformLogic->create($request);
    }

    #[PostMapping(path: 'modify')]
    #[ApiOperation('更新管理台')]
    public function modify(#[Valid] #[RequestBody] PlatformModifyRequest $request): BaseSuccessResponse
    {
        return $this->platformLogic->modify($request);
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除管理台')]
    public function remove(#[Valid] #[RequestBody] PlatformRemoveRequest $request): BaseSuccessResponse
    {
        return $this->platformLogic->remove($request);
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理台详情')]
    public function detail(#[Valid] #[RequestBody] PlatformDetailRequest $request): PlatformDetailResponse
    {
        return $this->platformLogic->detail($request);
    }
}
