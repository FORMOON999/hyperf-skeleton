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
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Middleware\PlatformMiddleware;
use App\Common\Util\Auth\Annotation\RouterAuthAnnotation;
use App\Entity\Request\Platform\V1\Login\LoginRequest;
use App\Entity\Response\Platform\V1\Login\LoginResponse;
use App\Logic\Platform\V1\LoginLogic;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;

#[Controller(prefix: 'api/v1/platform')]
#[Api(tags: 'Platform/管理台/登录')]
#[Middleware(PlatformMiddleware::class)]
class LoginController extends BaseController
{
    #[Inject()]
    protected LoginLogic $loginLogic;

    #[ApiOperation('登录')]
    #[PostMapping(path: 'login')]
    #[RouterAuthAnnotation(isPublic: true)]
    public function login(#[RequestBody] #[Valid] LoginRequest $request): LoginResponse
    {
        return $this->loginLogic->login($request);
    }

    #[ApiOperation('刷新token')]
    #[PostMapping(path: 'refreshToken')]
    #[ApiHeader(name: 'Authorization')]
    #[RouterAuthAnnotation(ignoreExpired: true)]
    public function refreshToken(): LoginResponse
    {
        $token = $this->request->getAttribute('token');
        return $this->loginLogic->refreshToken($token);
    }

    #[ApiOperation('退出登录')]
    #[PostMapping(path: 'logout')]
    #[ApiHeader(name: 'Authorization')]
    public function logout(): BaseSuccessResponse
    {
        $token = $this->request->getAttribute('token');
        return $this->loginLogic->logout($token);
    }

}


