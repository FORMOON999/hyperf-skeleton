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
use Hyperf\AsyncQueue\Listener\QueueLengthListener;
use Hyperf\AsyncQueue\Listener\ReloadChannelListener;
use Hyperf\Command\Listener\FailToHandleListener;
use Hyperf\ExceptionHandler\Listener\ErrorExceptionHandler;
use Hyperf\ModelCache\Listener\EagerLoadListener;

/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    ErrorExceptionHandler::class,
    FailToHandleListener::class,
    QueueLengthListener::class,
    ReloadChannelListener::class,
    EagerLoadListener::class,
];
