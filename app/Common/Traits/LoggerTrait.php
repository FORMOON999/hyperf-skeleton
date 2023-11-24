<?php

declare(strict_types=1);

namespace App\Common\Traits;

use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

trait LoggerTrait
{
    protected ?LoggerInterface $logger = null;

    public function getLogger(string $name = 'hyperf', string $group = 'default'): LoggerInterface
    {
        if ($this->logger === null) {
            $this->logger = \Hyperf\Support\make(LoggerFactory::class)->get($name, $group);
        }
        return $this->logger;
    }
}