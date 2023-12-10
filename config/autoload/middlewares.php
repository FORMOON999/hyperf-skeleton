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

use App\Common\Middleware\CorsMiddleware;
use App\Common\Middleware\DebugLogMiddleware;

return [
    'http' => [
        CorsMiddleware::class,
        DebugLogMiddleware::class,
    ],
];
