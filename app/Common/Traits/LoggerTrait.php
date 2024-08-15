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

namespace App\Common\Traits;

use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

trait LoggerTrait
{

    /**
     * @var LoggerInterface[]
     */
    protected array $logger;

    public function getLogger(string $name = 'hyperf', string $group = 'error'): LoggerInterface
    {
        if (empty($this->logger[$name])) {
            $this->logger[$name] = \Hyperf\Support\make(LoggerFactory::class)->get($name, $group);
        }
        return $this->logger[$name];
    }
}
