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

namespace App\Logic\App\V1;

use App\Common\Core\Entity\BaseSuccessResponse;
use App\Entity\Request\App\V1\Admin\AdminCreateRequest;
use App\Entity\Request\App\V1\Admin\AdminDetailRequest;
use App\Entity\Request\App\V1\Admin\AdminListRequest;
use App\Entity\Request\App\V1\Admin\AdminModifyRequest;
use App\Entity\Request\App\V1\Admin\AdminRemoveRequest;
use App\Entity\Response\App\V1\Admin\AdminDetailResponse;
use App\Entity\Response\App\V1\Admin\AdminListResponse;
use App\Infrastructure\AdminInterface;
use App\Service\AdminService;
use Hyperf\Di\Annotation\Inject;

class AdminLogic
{
    #[Inject()]
    protected AdminInterface $adminService;

    public function getList(AdminListRequest $request): AdminListResponse
    {
        $result = $this->adminService->getList(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'username',
                'password',
                'status',
                'created_at',
                'updated_at',
            ],
            $request->sort->setUnderlineName()->toArray(),
            $request->page->toArray(),
        );
        return new AdminListResponse($result);
    }

    public function create(AdminCreateRequest $request): BaseSuccessResponse
    {
        $this->adminService->create($request->condition->setHumpName()->toArray(), $request->data->setUnderlineName()->toArray());
        return new BaseSuccessResponse();
    }

    public function modify(AdminModifyRequest $request): BaseSuccessResponse
    {
        $this->adminService->modify(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            $request->data->setUnderlineName()->toArray()
        );
        return new BaseSuccessResponse();
    }

    public function remove(AdminRemoveRequest $request): BaseSuccessResponse
    {
        $this->adminService->remove($request->condition->setHumpName()->toArray(), $request->search->setUnderlineName()->toArray());
        return new BaseSuccessResponse();
    }

    public function detail(AdminDetailRequest $request): AdminDetailResponse
    {
        $result = $this->adminService->detail(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'username',
                'password',
                'status',
                'created_at',
                'updated_at',
            ],
        );
        return new AdminDetailResponse($result);
    }
}
