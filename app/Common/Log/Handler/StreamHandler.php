<?php

declare(strict_types=1);

namespace App\Common\Log\Handler;

use Monolog\Handler\StreamHandler as HyperfStreamHandler;
use Monolog\Logger;

class StreamHandler extends HyperfStreamHandler
{
    protected string $group;

    public function __construct($stream, $level = Logger::DEBUG, bool $bubble = true, ?int $filePermission = null, bool $useLocking = false, string $group = 'default')
    {
        $this->group = $group;
        parent::__construct($stream, $level, $bubble, $filePermission, $useLocking);
    }

    public function handle(array $record): bool
    {
        $record['channel'] = "{$this->group}-{$record['channel']}";
        return parent::handle($record);
    }
}