<?php

declare(strict_types=1);

namespace App\Logic\Platform\V1;

use App\Common\Core\Entity\BaseLogic;
use App\Common\Exceptions\BusinessException;
use Hyperf\Di\Annotation\Inject;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Constants\Errors\PlatformLoginRecordError;
use App\Infrastructure\PlatformLoginRecordInterface;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListResponse;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordCreateRequest;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordDetailRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordDetailResponse;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordModifyRequest;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordRemoveRequest;
use App\Model\PlatformLoginRecordEntity;

class PlatformLoginRecordLogic extends BaseLogic
{
    #[Inject()]
    protected PlatformLoginRecordInterface $platformLoginRecord;

    public function getList(PlatformLoginRecordListRequest $request): PlatformLoginRecordListResponse
    {
        $result = $this->platformLoginRecord->getList(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            [
    'id',
    'created_at',
    'updated_at',
    'deleted_at',
    'platform_id',
    'ip',
    'address',
    'address1',
    'address2',
],
            $request->sort->setUnderlineName()->toArray(),
            $request->page->toArray(),
        );
        $result->list = $this->toArray($result->list, function ($data) {
            return $this->format($data);
        });
        return new PlatformLoginRecordListResponse($result);
    }

    public function create(PlatformLoginRecordCreateRequest $request): BaseSuccessResponse
    {
        $result = $this->platformLoginRecord->create($request->data->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformLoginRecordError::CREATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function modify(PlatformLoginRecordModifyRequest $request): BaseSuccessResponse
    {
        $result = $this->platformLoginRecord->modify(
            $request->search->setUnderlineName()->toArray(),
            $request->data->setUnderlineName()->toArray()
        );
        if (! $result) {
            throw new BusinessException(PlatformLoginRecordError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function remove(PlatformLoginRecordRemoveRequest $request): BaseSuccessResponse
    {
        $result = $this->platformLoginRecord->remove($request->search->setUnderlineName()->toArray());
        if (! $result) {
            throw new BusinessException(PlatformLoginRecordError::DELETE_ERROR());
        }
        return new BaseSuccessResponse();
    }

    public function detail(PlatformLoginRecordDetailRequest $request): PlatformLoginRecordDetailResponse
    {
        $result = $this->platformLoginRecord->detail(
            $request->condition->setHumpName()->toArray(),
            $request->search->setUnderlineName()->toArray(),
            [
    'id',
    'created_at',
    'updated_at',
    'deleted_at',
    'platform_id',
    'ip',
    'address',
    'address1',
    'address2',
],
        );
        if (! $result) {
            throw new BusinessException(PlatformLoginRecordError::NOT_FOUND());
        }
        return new PlatformLoginRecordDetailResponse($this->format($result));
    }

    /**
     * @param PlatformLoginRecordEntity $result 数据
     * @return PlatformLoginRecordEntity
     */
    public function format(PlatformLoginRecordEntity $result): PlatformLoginRecordEntity
    {
        return $result;
    }
}