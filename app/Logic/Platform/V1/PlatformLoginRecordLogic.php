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

use App\Common\Core\BaseLogic;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListResponse;
use App\Infrastructure\PlatformLoginRecordInterface;
use App\Model\PlatformLoginRecordEntity;
use Hyperf\Di\Annotation\Inject;

class PlatformLoginRecordLogic extends BaseLogic
{
    #[Inject()]
    protected PlatformLoginRecordInterface $platformLoginRecord;

    public function getList(PlatformLoginRecordListRequest $request): PlatformLoginRecordListResponse
    {
        $result = $this->platformLoginRecord->getList(
            ['platform'],
            $request->search->setUnderlineName()->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
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

    /**
     * @param PlatformLoginRecordEntity $result 数据
     */
    public function format(PlatformLoginRecordEntity $result): PlatformLoginRecordEntity
    {
        return $result;
    }
}
