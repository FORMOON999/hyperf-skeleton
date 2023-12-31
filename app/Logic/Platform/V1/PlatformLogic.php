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
use App\Controller\Platform\V1\Platform\Request\PlatformCreateRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformDetailRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformListRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformModifyRequest;
use App\Controller\Platform\V1\Platform\Request\PlatformRemoveRequest;
use App\Controller\Platform\V1\Platform\Response\PlatformDetailResponse;
use App\Controller\Platform\V1\Platform\Response\PlatformListResponse;
use App\Infrastructure\PlatformInterface;
use Hyperf\Di\Annotation\Inject;

class PlatformLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    public function getList(PlatformListRequest $request): PlatformListResponse
    {
        $result = $this->platform->getList(
            $request->search?->setUnderlineName()?->toArray() ?? [],
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
                'last_time',
            ],
            [],
            $request->sort?->setUnderlineName()?->toArray() ?? [],
            $request->page?->toArray() ?? [],
        );
        return new PlatformListResponse($result);
    }

    public function create(PlatformCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->create($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function modify(int $id, PlatformModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->modify(
            ['id' => $id],
            $request->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(PlatformError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function remove(PlatformRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->remove($request->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function detail(PlatformDetailRequest $request): PlatformDetailResponse
    {
        $result = $this->platform->detail(
            $request->setUnderlineName()->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
                'last_time',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        return new PlatformDetailResponse($result);
    }
}
