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

namespace App\Controller\Admin\V1\Auth;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Common\Helpers\AesHelper;
use App\Common\Middleware\AdminMiddleware;
use App\Common\Util\Auth\Annotation\RouterAuthAnnotation;
use App\Common\Util\Auth\LoginFactory;
use App\Common\Util\Auth\LoginInterface;
use App\Constants\Errors\PlatformError;
use App\Controller\Admin\V1\Auth\Request\LoginRequest;
use App\Controller\Admin\V1\Auth\Response\CaptchaResponse;
use App\Controller\Admin\V1\Auth\Response\LoginResponse;
use App\Infrastructure\PlatformInterface;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\Di\Annotation\Inject;
use Hyperf\DTO\Annotation\Contracts\RequestBody;
use Hyperf\DTO\Annotation\Contracts\Valid;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\Container\ContainerInterface;
use Sunsgne\HyperfCaptcha\Captcha;

#[Controller(prefix: 'api/v1/admin/auth')]
#[Api(tags: 'Admin/登录')]
#[Middleware(AdminMiddleware::class)]
class LoginController extends BaseController
{
    #[Inject()]
    protected PlatformInterface $platform;

    #[Inject()]
    protected Captcha $captcha;

    protected LoginInterface $login;

    #[Inject]
    protected AesHelper $aesHelper;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $this->login = $container->get(LoginFactory::class)->get();
    }

    #[ApiOperation('登录')]
    #[PostMapping(path: 'login')]
    #[RouterAuthAnnotation(isPublic: true)]
    public function login(#[RequestBody] #[Valid] LoginRequest $request): LoginResponse
    {
        $code = $this->aesHelper->decrypt($request->captchaKey);
        if ($code !== $request->captchaCode) {
            throw new BusinessException(PlatformError::CAPTCHA_ERROR());
        }
        $result = $this->platform->login($request->username, $request->password);
        $token = $this->login->makeToke((string) $result->id, AdminMiddleware::getIss());
        return $this->loginResponse($token);
    }

    #[ApiOperation('验证码')]
    #[GetMapping(path: 'captcha')]
    #[RouterAuthAnnotation(isPublic: true)]
    public function captcha(): CaptchaResponse
    {
        $captcha = $this->captcha->create('math');
        $response = new CaptchaResponse();
        $response->captchaBase64 = $captcha['img'];
        $response->captchaKey = $this->aesHelper->encrypt($captcha['key']);
        return $response;
    }

    #[ApiOperation('刷新token')]
    #[PostMapping(path: 'refreshToken')]
    #[ApiHeader(name: 'Authorization')]
    #[RouterAuthAnnotation(ignoreExpired: true)]
    public function refreshToken(): LoginResponse
    {
        $token = $this->request->getAttribute('token');
        $result = $this->login->refreshToken($token);
        return $this->loginResponse($result);
    }

    #[ApiOperation('退出登录')]
    #[DeleteMapping(path: 'logout')]
    #[ApiHeader(name: 'Authorization')]
    public function logout(): BaseSuccessResponse
    {
        $token = $this->request->getAttribute('token');
        $this->login->logout($token);
        return new BaseSuccessResponse();
    }

    protected function loginResponse(string $token): LoginResponse
    {
        $response = new LoginResponse();
        $response->accessToken = $token;
        $response->expires = $this->login->getTtl();
        return $response;
    }
}
