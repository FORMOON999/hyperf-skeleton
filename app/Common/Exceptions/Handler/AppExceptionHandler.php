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

namespace App\Common\Exceptions\Handler;

use App\Common\Core\Enum\CommonError;
use App\Common\Core\Response;
use App\Common\Exceptions\BusinessException;
use App\Common\Traits\ExceptionFormatTrait;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    use ExceptionFormatTrait;

    protected LoggerInterface $logger;

    protected Response $response;

    public function __construct(LoggerFactory $loggerFactory, Response $response)
    {
        $this->logger = $loggerFactory->get('error', 'error');
        $this->response = $response;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        if (! $throwable instanceof NotFoundHttpException) {
            $this->logger->error($this->formatException($throwable));
        }

        if ($throwable instanceof BusinessException) {
            return $this->response->fail($throwable->getCode(), $throwable->getMessage());
        }

        if (\Hyperf\Support\env('APP_ENV') != 'prod') {
            return $this->response->fail(CommonError::SERVER_ERROR, $this->formatException($throwable));
        }

        $systemError = new BusinessException(CommonError::SERVER_ERROR);
        return $this->response->fail($systemError->getCode(), $systemError->getMessage());
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
