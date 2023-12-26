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

use App\Common\Constants\BaseStatus;
use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Common\Middleware\PlatformMiddleware;
use App\Common\Util\Auth\LoginFactory;
use App\Common\Util\Auth\LoginInterface;
use App\Constants\Errors\PlatformError;
use App\Entity\Request\Platform\V1\Login\LoginRequest;
use App\Entity\Response\Platform\V1\Login\LoginResponse;
use App\Event\PlatformLoginEvent;
use App\Infrastructure\PlatformInterface;
use Hyperf\Di\Annotation\Inject;
use Lengbin\Helper\Util\PasswordHelper;
use Psr\EventDispatcher\EventDispatcherInterface;

class LoginLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    #[Inject()]
    protected EventDispatcherInterface $eventDispatcher;

    protected LoginInterface $login;

    public function __construct(LoginFactory $loginFactory)
    {
        $this->login = $loginFactory->get();
    }

    public function login(LoginRequest $request): LoginResponse
    {
        $platform = $this->platform->detail([], ['username' => $request->data->username], [
            'id',
            'password',
            'status',
        ]);
        if (empty($platform)) {
            throw new BusinessException(PlatformError::ACCOUNT_OR_PASSWORD_NOT_FOUND());
        }

        if (! PasswordHelper::verifyPassword($request->data->password, $platform->password)) {
            throw new BusinessException(PlatformError::ACCOUNT_OR_PASSWORD_NOT_FOUND());
        }
        // 状态
        if ($platform->status !== BaseStatus::NORMAL()) {
            throw new BusinessException(PlatformError::FROZEN());
        }

        // 登录日志
        $this->eventDispatcher->dispatch(new PlatformLoginEvent($platform->id));
        $token = $this->login->makeToke((string) $platform->id, PlatformMiddleware::getIss());
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

    protected function formatToken(string $token): LoginResponse
    {
        $response = new LoginResponse();
        $response->token = $token;
        $response->expire = $this->login->getTtl();
        return $response;
    }
}
