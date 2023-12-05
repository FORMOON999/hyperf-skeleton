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

namespace App\Controller\App\V1;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Entity\Request\App\V1\Admin\AdminCreateRequest;
use App\Entity\Request\App\V1\Admin\AdminDetailRequest;
use App\Entity\Request\App\V1\Admin\AdminListRequest;
use App\Entity\Request\App\V1\Admin\AdminModifyRequest;
use App\Entity\Request\App\V1\Admin\AdminRemoveRequest;
use App\Entity\Response\App\V1\Admin\AdminDetailResponse;
use App\Entity\Response\App\V1\Admin\AdminListResponse;
use App\Logic\App\V1\AdminLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/app/admin')]
#[Api(tags: 'App/管理员管理')]
#[Middleware(AppMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class AdminController extends BaseController
{
    #[Inject]
    protected AdminLogic $adminLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取管理员列表')]
    public function getList(#[RequestBody] #[Valid] AdminListRequest $request): AdminListResponse
    {
        return $this->adminLogic->getList($request);
    }

    #[PostMapping(path: 'create')]
    #[ApiOperation('创建管理员')]
    public function create(#[Valid] #[RequestBody] AdminCreateRequest $request): BaseSuccessResponse
    {
        return $this->adminLogic->create($request);
    }

    #[PostMapping(path: 'modify')]
    #[ApiOperation('更新管理员')]
    public function modify(#[Valid] #[RequestBody] AdminModifyRequest $request): BaseSuccessResponse
    {
        return $this->adminLogic->modify($request);
    }

    #[PostMapping(path: 'remove')]
    #[ApiOperation('删除管理员')]
    public function remove(#[Valid] #[RequestBody] AdminRemoveRequest $request): BaseSuccessResponse
    {
        return $this->adminLogic->remove($request);
    }

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理员详情')]
    public function detail(#[Valid] #[RequestBody] AdminDetailRequest $request): AdminDetailResponse
    {
        return $this->adminLogic->detail($request);
    }
}
