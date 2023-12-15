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

use App\Common\Core\Entity\BaseLogic;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\AdminError;
use App\Entity\Request\App\V1\Admin\AdminCreateRequest;
use App\Entity\Request\App\V1\Admin\AdminDetailRequest;
use App\Entity\Request\App\V1\Admin\AdminListRequest;
use App\Entity\Request\App\V1\Admin\AdminModifyRequest;
use App\Entity\Request\App\V1\Admin\AdminRemoveRequest;
use App\Entity\Response\App\V1\Admin\AdminDetailResponse;
use App\Entity\Response\App\V1\Admin\AdminListResponse;
use App\Infrastructure\AdminInterface;
use App\Model\AdminEntity;
use Hyperf\Di\Annotation\Inject;

class AdminLogic extends BaseLogic
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
        $result->list = $this->toArray($result->list, function ($data) {
            return $this->format($data);
        });
        return new AdminListResponse($result);
    }

    public function create(AdminCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->adminService->create($request->data->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(AdminError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function modify(AdminModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->adminService->modify(
            $request->search->setUnderlineName()->toArray(),
            $request->data->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(AdminError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function remove(AdminRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->adminService->remove($request->search->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(AdminError::DELETE_ERROR());
        }
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
        if (! $result) {
            throw new BusinessException(AdminError::NOT_FOUND());
        }
        return new AdminDetailResponse($this->format($result));
    }

    protected function format(AdminEntity $result): AdminEntity
    {
        return $result;
    }

    /**
     * @param array $condition 控制参数
     * @param array $data 数据
     */
    protected function validate(array $condition, array $data, array $search = []): array
    {
        return $data;
    }
}
