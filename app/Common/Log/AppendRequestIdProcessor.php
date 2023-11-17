<?php
/**
 * Created by PhpStorm.
 * Date:  2021/9/2
 * Time:  5:13 下午.
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

namespace App\Common\Log;

use Hyperf\Context\Context;
use Hyperf\Coroutine\Coroutine;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class AppendRequestIdProcessor implements ProcessorInterface
{
    public const REQUEST_ID = 'context.request.id';

    public function __invoke(array|LogRecord $record): array|LogRecord
    {
        $uuid = uniqid(\Hyperf\Config\config('app_name'), true);
        $record['context']['request_id'] = Context::getOrSet(self::REQUEST_ID, $uuid);
        $record['context']['coroutine_id'] = Coroutine::id();
        return $record;
    }
}
