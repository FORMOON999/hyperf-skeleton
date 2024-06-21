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

use App\Common\Util\IPC\BaseIPC;
use App\Common\Util\IPC\PipeMessageInterface;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;

class ConfigIPC extends BaseIPC
{
    #[Inject]
    protected ConfigInterface $config;

    public function synConfig(PipeConfigInterface $pipeConfig): void
    {
        $this->shareConfigToProcesses($pipeConfig);
    }

    public function update(PipeMessageInterface $config): void
    {
        foreach ($config->getData() as $key => $value) {
            if (is_string($key)) {
                $this->config->set($key, $value);
            }
        }
    }
}
