<?php
/**
 * Created by PhpStorm.
 * Date:  2022/2/18
 * Time:  4:02 PM.
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

use App\Common\Http\Response;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidateExceptionHandler extends ExceptionHandler
{
    protected Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Handle the exception, and return the specified result.
     *
     * @param ValidationException $throwable
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        $systemError = new BusinessException(CommonError::INVALID_PARAMS());
        $message = $throwable->validator->errors()->first();
        return $this->response->fail($systemError->getCode(), $message);
    }

    /**
     * Determine if the current exception handler should handle the exception,.
     *
     * @return bool
     *              If return true, then this exception handler will handle the exception,
     *              If return false, then delegate to next handler
     */
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
