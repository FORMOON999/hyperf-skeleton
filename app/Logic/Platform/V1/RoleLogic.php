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

namespace App\Logic\Platform\V1;

use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\RoleError;
use App\Controller\Platform\V1\Role\Request\RoleCreateRequest;
use App\Controller\Platform\V1\Role\Request\RoleDetailRequest;
use App\Controller\Platform\V1\Role\Request\RoleListRequest;
use App\Controller\Platform\V1\Role\Request\RoleModifyRequest;
use App\Controller\Platform\V1\Role\Request\RoleRemoveRequest;
use App\Controller\Platform\V1\Role\Response\RoleDetailResponse;
use App\Controller\Platform\V1\Role\Response\RoleListResponse;
use App\Infrastructure\RoleInterface;
use Hyperf\Di\Annotation\Inject;

class RoleLogic
{
    #[Inject()]
    protected RoleInterface $role;

    public function getList(RoleListRequest $request): RoleListResponse
    {
        $result = $this->role->getList(
            $request->search?->setUnderlineName()?->toArray() ?? [],
            [
                'id',
                'created_at',
                'updated_at',
                'name',
                'code',
                'sort',
                'status',
            ],
            [],
            $request->sort?->setUnderlineName()?->toArray() ?? [],
            $request->page?->toArray() ?? [],
        );
        return new RoleListResponse($result);
    }

    public function create(RoleCreateRequest $request): BaseSuccessResponse
    {
        $this->check($request->code);
        $result = $this->role->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(RoleError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function modify(int $id, RoleModifyRequest $request): BaseSuccessResponse
    {
        $this->check($request->code, $id);
        $result = $this->role->modify(
            ['id' => $id],
            $request->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(RoleError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function remove(RoleRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->role->remove($request->toArray());
        if (! $result) {
            throw new BusinessException(RoleError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function detail(RoleDetailRequest $request): RoleDetailResponse
    {
        $result = $this->role->detail(
            $request->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'name',
                'code',
                'sort',
                'status',
            ],
        );
        if (! $result) {
            throw new BusinessException(RoleError::NOT_FOUND());
        }
        return new RoleDetailResponse($result);
    }

    protected function check(string $code, int $id = 0): void
    {
        $search = [
            ['code', '=', $code],
        ];
        if ($id > 0) {
            $search[] = ['id', '!=', $id];
        }
        $result = $this->role->detail($search, ['id']);
        if ($result) {
            throw new BusinessException(RoleError::EXISTS(), '', ['name' => $code]);
        }
    }
}
