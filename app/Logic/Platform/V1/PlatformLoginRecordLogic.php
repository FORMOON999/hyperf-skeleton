<?php

declare(strict_types=1);

namespace App\Logic\Platform\V1;

use App\Common\Core\BaseLogic;
use Hyperf\Di\Annotation\Inject;
use App\Infrastructure\PlatformLoginRecordInterface;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListResponse;

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
        return new PlatformLoginRecordListResponse($result);
    }
}