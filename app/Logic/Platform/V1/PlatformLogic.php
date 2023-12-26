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
use App\Constants\Errors\PlatformError;
use App\Entity\Request\Platform\V1\Platform\PlatformCreateRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformDetailRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformListRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformModifyRequest;
use App\Entity\Request\Platform\V1\Platform\PlatformRemoveRequest;
use App\Entity\Response\Platform\V1\Platform\PlatformDetailResponse;
use App\Entity\Response\Platform\V1\Platform\PlatformListResponse;
use App\Infrastructure\PlatformInterface;
use Hyperf\Di\Annotation\Inject;

class PlatformLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    public function getList(PlatformListRequest $request): PlatformListResponse
    {
        $result = $this->platform->getList(
            [],
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
            ],
            $request->sort->setUnderlineName()->toArray(),
            $request->page->toArray(),
        );
        return new PlatformListResponse($result);
    }

    public function create(PlatformCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->create($request->data->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function modify(PlatformModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->modify(
            $request->search->setUnderlineName()->toArray(),
            $request->data->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(PlatformError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function remove(PlatformRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->remove($request->search->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function detail(PlatformDetailRequest $request): PlatformDetailResponse
    {
        $result = $this->platform->detail(
            [],
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        return new PlatformDetailResponse($result);
    }

    public function me(int $id): PlatformDetailResponse
    {
        $result = $this->platform->detail(
            [],
            ['id' => $id],
            [
                'id',
                'created_at',
                'username',
                'nickname',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }

        return new PlatformDetailResponse($result);
    }
}
