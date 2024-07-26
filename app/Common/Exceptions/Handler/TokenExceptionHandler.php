<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/15
 * Time:  10:39 PM.
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

namespace App\Common\Exceptions\Handler;

use App\Common\Core\Enum\CommonError;
use App\Common\Core\Response;
use App\Common\Util\Auth\Exception\InvalidTokenException;
use App\Common\Util\Auth\Exception\TokenExpireException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class TokenExceptionHandler extends ExceptionHandler
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        $error = CommonError::INVALID_TOKEN;
        return $this->response->fail($error->value, $error->getMessage());
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof InvalidTokenException || $throwable instanceof TokenExpireException;
    }
}
