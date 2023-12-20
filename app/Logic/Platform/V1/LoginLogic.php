<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/14
 * Time:  10:43 AM.
 */

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
use App\Common\Middleware\PlatformMiddleware;
use App\Common\Util\Auth\LoginFactory;
use App\Common\Util\Auth\LoginInterface;
use App\Service\PlatformService;
use Hyperf\Di\Annotation\Inject;
use Psr\EventDispatcher\EventDispatcherInterface;

class LoginLogic extends BaseLogic
{
    #[Inject()]
    protected PlatformService $platformService;
    #[Inject()]
    protected EventDispatcherInterface $eventDispatcher;

    protected LoginInterface $login;

    public function __construct(LoginFactory $loginFactory)
    {
        $this->login = $loginFactory->get();
    }
    public function login(LoginRequest $request): LoginResponse
    {
        $platform = $this->getPlatform($request->data->address);
        if (empty($platform)) {
            throw new BusinessException(PlatformError::ADDRESS_NOT_FOUND);
        }
        $code = $this->googleAuthenticator->getCode($platform["secret"]);
        // 验证签名
        if (!$this->sign->verify($request->data->address, $code, $request->data->sign)) {
            throw new BusinessException(PlatformError::SIGN_ERROR);
        }
        // 状态
        if ($platform['status'] !== BaseStatus::NORMAL) {
            throw new BusinessException(PlatformError::FROZEN);
        }

        // 登录日志
        $this->eventDispatcher->dispatch(new PlatformLoginEvent($platform['platform_id']));

        $token = $this->login->makeToke((string)$platform['platform_id'], PlatformMiddleware::getIss());
        return $this->formatToken($token);
    }

    public function refreshToken(string $token): LoginResponse
    {
        $result = $this->login->refreshToken($token);
        return $this->formatToken($result);
    }

    public function logout(string $token): BaseSuccessResponse
    {
        $result = $this->login->logout($token);
        $response = new BaseSuccessResponse();
        $response->result = $result;
        return $response;
    }

    protected function getPlatform(string $address): array
    {
        return $this->platformService->detail([
            '_notThrow' => 0,
        ], [
            'enable' => SoftDeleted::ENABLE,
            'address' => $address,
        ], [
            'nonce',
            'platform_id',
            'status',
            "secret"
        ]);
    }

    protected function formatToken(string $token): LoginResponse
    {
        $response = new LoginResponse();
        $response->token = $token;
        $response->expire = $this->login->getTtl();
        return $response;
    }
}
