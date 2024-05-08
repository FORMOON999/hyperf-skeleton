<?php
/**
 * Created by PhpStorm.
 * Date:  2022/2/20
 * Time:  10:50 AM.
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

namespace App\Common\Util\Auth\Middleware;

use App\Common\Util\Auth\Annotation\RouterAuthAnnotation;
use App\Common\Util\Auth\Exception\InvalidTokenException;
use App\Common\Util\Auth\Exception\TokenExpireException;
use App\Common\Util\Auth\JwtSubject;
use App\Common\Util\Auth\LoginFactory;
use App\Common\Util\Auth\LoginInterface;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

abstract class BaseAuthMiddleware implements MiddlewareInterface
{
    // 登录模型
    protected string $loginMode = LoginFactory::LOGIN_MODE_API;

    protected LoginInterface $login;

    protected LoggerInterface $logger;

    public function __construct()
    {
        $this->login = \Hyperf\Support\make(LoginFactory::class)->get($this->loginMode);
        $this->logger = \Hyperf\Support\make(LoggerFactory::class)->get('jwt-payload');
    }

    public function getTokenByRequest(ServerRequestInterface $request, string $key = 'authorization'): string
    {
        $token = $request->getHeaderLine($key);
        if (empty($token)) {
            $token = $request->getQueryParams()[$key] ?? '';
        }
        if (empty($token)) {
            $token = $request->getCookieParams()[$key] ?? '';
        }
        if (empty($token)) {
            $token = $request->getParsedBody()[$key] ?? '';
        }
        return $token;
    }

    /**
     * 获取Token，  可以 复写 自定义 获取key.
     */
    public function getToken(ServerRequestInterface $request): ?string
    {
        $token = $this->getTokenByRequest($request);
        [$token] = sscanf($token, 'Bearer %s');
        return $token;
    }

    /**
     * 解析 jwt 数据， 可以 复写 自己验证 token.
     */
    public function validateToken(?string $token, bool $ignoreExpired): JwtSubject
    {
        return $this->getLoginMode()->verifyToken($token, $ignoreExpired);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        [$isPublic, $isWhite, $ignoreExpired] = $this->checkRouter($request);

        // 无需鉴权 1，公开的， 2白名单的
        $token = $this->getToken($request);
        if ($isPublic || (empty($token) && $isWhite)) {
            return $handler->handle($request);
        }

        $isTest = \Hyperf\Support\env('APP_ENV') != 'prod' && $token == '8888';
        if ($isTest) {
            $payload = new JwtSubject();
            $results = $this->getTestPayload($request);
        } else {
            $payload = $this->validateToken($token, $ignoreExpired);
            $results = $this->handlePayload($request, $payload);
        }

        // 记录 jwt解析 日志
        if (\Hyperf\Config\config('auth.enable_log', true)) {
            $requestPayload = get_object_vars($payload);
            $requestPayload['token'] = $token;
            $this->logger->info(json_encode($requestPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        if ($payload->invalid || (! $isTest && ! empty(static::getIss()) && static::getIss() !== ($payload->data['iss'] ?? ''))) {
            throw new InvalidTokenException();
        }

        if ($payload->expired) {
            throw new TokenExpireException();
        }

        $results['token'] = $token;
        foreach ($results as $key => $value) {
            $request = $request->withAttribute($key, $value);
        }
        Context::set(ServerRequestInterface::class, $request);
        return $handler->handle($request);
    }

    /**
     * jwt签发者, 如果为空 则使用 当前登录uri.
     */
    abstract public static function getIss(): ?string;

    /**
     * 检测注解路由， 实现路由白名单.
     */
    protected function checkRouter(ServerRequestInterface $request): array
    {
        $dispatched = $request->getAttribute(Dispatched::class);
        $annotation = RouterAuthAnnotation::class;
        $callback = $dispatched->handler->callback;
        [$class, $method] = is_array($callback) ? $callback : ['', ''];
        $annotations = AnnotationCollector::getClassMethodAnnotation($class, $method);
        $authAnnotation = $annotations[$annotation] ?? AnnotationCollector::getClassAnnotation($class, $annotation);

        $isPublic = $isWhite = $ignoreExpired = false;
        if ($authAnnotation !== null) {
            $isPublic = $authAnnotation->isPublic;
            $isWhite = $authAnnotation->isWhite;
            $ignoreExpired = $authAnnotation->ignoreExpired;
        }
        return [$isPublic, $isWhite, $ignoreExpired];
    }

    protected function getLoginMode(): LoginInterface
    {
        return $this->login;
    }

    abstract protected function getTestPayload(ServerRequestInterface $request): array;

    /**
     * 处理数据.
     */
    abstract protected function handlePayload(ServerRequestInterface $request, JwtSubject $payload): array;
}
