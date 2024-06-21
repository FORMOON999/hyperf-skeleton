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

namespace App\Common\Util\IPC;

use App\Common\Util\IPC\Config\ConfigIPC;
use App\Common\Util\IPC\Config\PipeConfig;
use App\Common\Util\IPC\Config\PipeConfigInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Framework\Event\OnPipeMessage;
use Hyperf\Process\Event\PipeMessage as UserProcessPipeMessage;
use Psr\Container\ContainerInterface;

// ipc config listener
class IPCListener implements ListenerInterface
{
    public function __construct(protected ContainerInterface $container) {}

    public function listen(): array
    {
        return [
            BootApplication::class,
            OnPipeMessage::class,
            UserProcessPipeMessage::class,
        ];
    }

    public function process(object $event): void
    {
        $ipc = $this->container->get(ConfigIPC::class);
        if ($event instanceof BootApplication) {
            $ipc->update(new PipeConfig(['tConfig' => 'test']));
        }

        if ($event instanceof OnPipeMessage || $event instanceof UserProcessPipeMessage) {
            $event->data instanceof PipeConfigInterface && $ipc->update($event->data);
        }
    }
}
