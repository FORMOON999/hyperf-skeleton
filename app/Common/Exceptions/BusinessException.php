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

namespace App\Common\Exceptions;

use App\Common\Core\Enum\MessageBackedEnum;
use Hyperf\Server\Exception\ServerException;
use Throwable;

class BusinessException extends ServerException
{
    public function __construct(int|MessageBackedEnum $code, ?string $message = null, array $replace = [], ?Throwable $previous = null)
    {
        if ($code instanceof MessageBackedEnum) {
            if (empty($message)) {
                $message = $code->getMessage($replace);
            }
            $code = $code->value;
        }
        parent::__construct($message, $code, $previous);
    }
}
