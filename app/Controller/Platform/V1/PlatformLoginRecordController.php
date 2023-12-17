<?php
declare(strict_types=1);

namespace App\Controller\Platform\V1;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
//use App\Common\Middleware\PlatformMiddleware;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use App\Logic\Platform\V1\PlatformLoginRecordLogic;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListResponse;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordCreateRequest;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordDetailRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordDetailResponse;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordModifyRequest;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordRemoveRequest;

#[Controller(prefix: 'api/v1/platform/platform/login/record')]
#[Api(tags: 'Platform/管理台登录日志管理')]
//#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class PlatformLoginRecordController extends BaseController
{
    #[Inject]
    protected PlatformLoginRecordLogic $platformLoginRecordLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取管理台登录日志列表')]
    public function getList(#[Valid] #[RequestBody] PlatformLoginRecordListRequest $request): PlatformLoginRecordListResponse
    {
        return $this->platformLoginRecordLogic->getList($request);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建管理台登录日志')]
    public function create(#[Valid] #[RequestBody] PlatformLoginRecordCreateRequest $request): BaseSuccessResponse
    {
        return $this->platformLoginRecordLogic->create($request);
    }

    #[PostMapping(path: 'modify')]
    #[ApiOperation('更新管理台登录日志')]
    public function modify(#[Valid] #[RequestBody] PlatformLoginRecordModifyRequest $request): BaseSuccessResponse
    {
        return $this->platformLoginRecordLogic->modify($request);
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除管理台登录日志')]
    public function remove(#[Valid] #[RequestBody] PlatformLoginRecordRemoveRequest $request): BaseSuccessResponse
    {
        return $this->platformLoginRecordLogic->remove($request);
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理台登录日志详情')]
    public function detail(#[Valid] #[RequestBody] PlatformLoginRecordDetailRequest $request): PlatformLoginRecordDetailResponse
    {
        return $this->platformLoginRecordLogic->detail($request);
    }
}