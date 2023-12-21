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

namespace App\Controller\Platform\V1;

use App\Common\Core\BaseController;
use App\Common\Middleware\PlatformMiddleware;
use App\Entity\Request\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListRequest;
use App\Entity\Response\Platform\V1\PlatformLoginRecord\PlatformLoginRecordListResponse;
use App\Logic\Platform\V1\PlatformLoginRecordLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/platform/login/record')]
#[Api(tags: 'Platform/管理台/管理台登录日志管理')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class PlatformLoginRecordController extends BaseController
{
    #[Inject]
    protected PlatformLoginRecordLogic $platformLoginRecordLogic;

    #[PostMapping(path: 'list')]
    #[ApiOperation('获取管理台登录日志列表')]
    public function getList(#[Valid] #[RequestBody] PlatformLoginRecordListRequest $request): PlatformLoginRecordListResponse
    {
        return $this->platformLoginRecordLogic->getList($request);
    }
}
