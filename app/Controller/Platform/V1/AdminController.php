<?php
declare(strict_types=1);

namespace App\Controller\Platform\V1;

use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use App\Common\BaseController;
use App\Common\Entity\Response\BaseSuccessResponse;
use App\Logic\Platform\V1\AdminLogic;
use App\Entity\Request\Platform\V1\Admin\AdminListRequest;
use App\Entity\Response\Platform\V1\Admin\AdminListResponse;
use App\Entity\Request\Platform\V1\Admin\AdminCreateRequest;
use App\Entity\Request\Platform\V1\Admin\AdminDetailRequest;
use App\Entity\Response\Platform\V1\Admin\AdminDetailResponse;
use App\Entity\Request\Platform\V1\Admin\AdminModifyRequest;
use App\Entity\Request\Platform\V1\Admin\AdminRemoveRequest;

#[Controller(prefix: "api/v1/platform/admin")]
#[Api(tags: "Platform/管理员管理")]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class AdminController extends BaseController
{
    #[Inject]
    protected AdminLogic $adminLogic;

    #[PostMapping(path: "list")]
    #[ApiOperation("获取管理员列表")]
    public function getList(#[Valid] #[RequestBody] AdminListRequest $request): AdminListResponse
    {
        return $this->adminLogic->getList($request);
    }

    #[PostMapping(path: "create")]
    #[ApiOperation("创建管理员")]
    public function create(#[Valid] #[RequestBody] AdminCreateRequest $request): BaseSuccessResponse
    {
        return $this->adminLogic->create($request);
    }

    #[PostMapping(path: "modify")]
    #[ApiOperation("更新管理员")]
    public function modify(#[Valid] #[RequestBody] AdminModifyRequest $request): BaseSuccessResponse
    {
        return $this->adminLogic->modify($request);
    }

    #[PostMapping(path: "remove")]
    #[ApiOperation("删除管理员")]
    public function remove(#[Valid] #[RequestBody] AdminRemoveRequest $request): BaseSuccessResponse
    {
        return $this->adminLogic->remove($request);
    }

    #[PostMapping(path: "detail")]
    #[ApiOperation("获取管理员详情")]
    public function detail(#[Valid] #[RequestBody] AdminDetailRequest $request): AdminDetailResponse
    {
        return $this->adminLogic->detail($request);
    }
}