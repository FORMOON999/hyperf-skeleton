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

use App\Controller\Platform\V1\PlatformLoginRecord\Request\PlatformLoginRecordListRequest;
use App\Controller\Platform\V1\PlatformLoginRecord\Response\PlatformLoginRecordListResponse;
use App\Infrastructure\PlatformLoginRecordInterface;
use Hyperf\Di\Annotation\Inject;

class PlatformLoginRecordLogic
{
    #[Inject()]
    protected PlatformLoginRecordInterface $platformLoginRecord;

    public function getList(PlatformLoginRecordListRequest $request): PlatformLoginRecordListResponse
    {
        $result = $this->platformLoginRecord->getList(
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
            ['platform'],
            $request->sort->setUnderlineName()->toArray(),
            $request->page->toArray(),
        );
        return new PlatformLoginRecordListResponse($result);
    }
}
