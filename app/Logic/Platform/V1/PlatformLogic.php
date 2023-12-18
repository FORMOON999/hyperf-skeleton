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

use App\Common\Core\Entity\BaseLogic;
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
use App\Model\PlatformEntity;
use Hyperf\Di\Annotation\Inject;

class PlatformLogic extends BaseLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    public function getList(PlatformListRequest $request): PlatformListResponse
    {
        $result = $this->platform->getList(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'password',
                'status',
            ],
            $request->sort->setUnderlineName()->toArray(),
            $request->page->toArray(),
        );
        $result->list = $this->toArray($result->list, function ($data) {
            return $this->format($data);
        });
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
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'password',
                'status',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        return new PlatformDetailResponse($this->format($result));
    }

    /**
     * @param PlatformEntity $result 数据
     */
    public function format(PlatformEntity $result): PlatformEntity
    {
        return $result;
    }
}
