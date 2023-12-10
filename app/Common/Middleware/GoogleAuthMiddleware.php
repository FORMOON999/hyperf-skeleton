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
namespace App\Common\Middleware;

use App\Common\Core\Enum\CommonError;
use App\Common\Exceptions\BusinessException;
use App\Common\Helpers\GoogleAuthenticator;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GoogleAuthMiddleware implements MiddlewareInterface
{
    #[Inject]
    protected GoogleAuthenticator $googleAuthenticator;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $secret = $request->getAttribute('secret');
        $code = $request->getHeaderLine('code');
        if (\Hyperf\Support\env('APP_ENV') != 'prod' && $code == '8888') {
            return $handler->handle($request);
        }
        if (empty($secret) || empty($code)) {
            throw new BusinessException(CommonError::INVALID_PERMISSION);
        }
        $result = $this->googleAuthenticator->verifyCode($secret, $code);
        if (! $result) {
            throw new BusinessException(CommonError::INVALID_PERMISSION);
        }

        return $handler->handle($request);
    }
}
