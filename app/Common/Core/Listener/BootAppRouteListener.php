<?php

declare(strict_types=1);

namespace App\Common\Core\Listener;

use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\Support\Filesystem\Filesystem;

#[Listener]
class BootAppRouteListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    public function __construct(protected Filesystem $filesystem)
    {
    }

    public function process(object $event): void
    {
        $path = BASE_PATH . '/runtime/container/classes.cache';
        $data = [];
        if ($this->filesystem->exists($path)) {
            $data = unserialize($this->filesystem->get($path));
        }
        var_dump($data);
        die;
    }
}
