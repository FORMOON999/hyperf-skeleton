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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AppMiddleware implements MiddlewareInterface
{
    protected string $key = 'hash';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $sign = $request->getHeaderLine('sign');
        if (in_array(\Hyperf\Support\env('APP_ENV'), ['dev', 'local'])) {
            return $handler->handle($request);
        }

        if ($sign !== $this->generateSign($request)) {
            throw new BusinessException(CommonError::INVALID_PERMISSION);
        }
        return $handler->handle($request);
    }

    protected function generateSign(ServerRequestInterface $request): string
    {
        $time = $request->getHeaderLine('time');
        $body = ! empty($request->getParsedBody()) ? $request->getBody()->getContents() : '{}';
        return sha1($body . $this->key . $time);
    }
}
