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
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\PlatformMiddleware;
use App\Controller\Platform\V1\Profile\Request\ChangePasswordRequest;
use App\Controller\Platform\V1\Profile\Request\ProfileDetailRequest;
use App\Controller\Platform\V1\Profile\Response\ProfileResponse;
use App\Logic\Platform\V1\ProfileLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
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
    protected ProfileLogic $profileLogic;

    #[PostMapping(path: 'detail')]
    #[ApiOperation('获取管理员详情')]
    public function detail(#[Valid] #[RequestBody] ProfileDetailRequest $request): ProfileResponse
    {
        return $this->profileLogic->detail($request);
    }

    #[PostMapping(path: 'changePassword')]
    #[ApiOperation('修改密码')]
    public function changePassword(#[Valid] #[RequestBody] ChangePasswordRequest $request): BaseSuccessResponse
    {
        return $this->profileLogic->changePassword($request);
    }
}
