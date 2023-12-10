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

use App\Common\Helpers\IpHelper;
use App\Common\Log\AppendRequestIdProcessor;
use Hyperf\Context\Context;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Snowflake\IdGenerator\SnowflakeIdGenerator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DebugLogMiddleware implements MiddlewareInterface
{
    #[Inject]
    protected SnowflakeIdGenerator $idGenerator;

    #[Inject]
    protected IpHelper $ipHelper;

    protected LoggerFactory $loggerFactory;

    protected string $logGroup = 'default';

    public function __construct()
    {
        $this->loggerFactory = \Hyperf\Support\make(LoggerFactory::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        Context::getOrSet(AppendRequestIdProcessor::REQUEST_ID, $this->idGenerator->generate());

        // 记录请求日志
        $this->loggerFactory->get('request', $this->logGroup)->info(json_encode([
            'user-agent' => $request->getHeaderLine('user-agent'),
            'ip' => $this->ipHelper->getClientIp(),
            'host' => $request->getUri()->getHost(),
            'url' => $request->getUri()->getPath(),
            'post' => $request->getParsedBody(),
            'get' => $request->getQueryParams(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $response = $handler->handle($request);

        $this->loggerFactory->get('response', $this->logGroup)->info($response->getBody()->getContents());
        return $response;
    }
}
