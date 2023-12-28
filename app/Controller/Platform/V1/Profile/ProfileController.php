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

namespace App\Controller\Platform\V1\Profile;

use App\Common\Core\BaseController;
use App\Common\Middleware\PlatformMiddleware;
use App\Controller\Platform\V1\Profile\Response\ProfileResponse;
use App\Logic\Platform\V1\ProfileLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/platform/profile')]
#[Api(tags: 'Platform/当前管理员信息')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class ProfileController extends BaseController
{
    #[Inject]
    protected ProfileLogic $platformLogic;

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理员详情')]
    public function detail(): ProfileResponse
    {
        $id = $this->request->getAttribute('id');
        return $this->platformLogic->detail(intval($id));
    }
}
