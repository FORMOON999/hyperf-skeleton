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

use App\Common\Exceptions\Handler\AppExceptionHandler;
use App\Common\Exceptions\Handler\TokenExceptionHandler;
use App\Common\Exceptions\Handler\ValidateExceptionHandler;

return [
    'handler' => [
        'http' => [
            TokenExceptionHandler::class,
            ValidateExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
