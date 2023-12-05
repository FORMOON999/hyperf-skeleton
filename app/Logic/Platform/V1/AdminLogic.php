<?php

declare(strict_types=1);

namespace App\Logic\Platform\V1;

use Hyperf\Di\Annotation\Inject;
use App\Common\BaseLogic;
use App\Common\Entity\Response\BaseSuccessResponse;
use App\Service\AdminService;
use App\Entity\Request\Platform\V1\Admin\AdminListRequest;
use App\Entity\Response\Platform\V1\Admin\AdminListResponse;
use App\Entity\Request\Platform\V1\Admin\AdminCreateRequest;
use App\Entity\Request\Platform\V1\Admin\AdminDetailRequest;
use App\Entity\Response\Platform\V1\Admin\AdminDetailResponse;
use App\Entity\Request\Platform\V1\Admin\AdminModifyRequest;
use App\Entity\Request\Platform\V1\Admin\AdminRemoveRequest;

class AdminLogic extends BaseLogic
{
    #[Inject()]
    protected AdminService $adminService;

    protected array $field = [
    'id',
    'username',
    'password',
    'status',
    'created_at',
    'updated_at',
    'deleted_at',
];

    public function getList(AdminListRequest $request): AdminListResponse
    {
        $result = $this->adminService->getList(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            $request->sort->setUnderlineName()->toArray(),
            $request->page,
            $this->field
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
            $this->field
        );
        return new AdminDetailResponse($result);
    }
}