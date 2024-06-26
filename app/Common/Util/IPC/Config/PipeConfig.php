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

namespace App\Common\Util\IPC\Config;

class PipeConfig implements PipeConfigInterface
{
    public function __construct(protected array $data) {}

    public function getData(): array
    {
        return $this->data;
    }
}
