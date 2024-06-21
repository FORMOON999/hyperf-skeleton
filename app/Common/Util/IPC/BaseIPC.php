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

use App\Common\Traits\LoggerTrait;
use Hyperf\Context\ApplicationContext;
use Hyperf\Process\ProcessCollector;
use Swoole\Process;
use Swoole\Server;

abstract class BaseIPC
{
    use LoggerTrait;

    protected ?Server $server = null;

    protected function shareConfigToProcesses(PipeMessageInterface $message): void
    {
        if (class_exists(ProcessCollector::class) && ! ProcessCollector::isEmpty()) {
            $this->shareMessageToWorkers($message);
            $this->shareMessageToUserProcesses($message);
        } else {
            $this->update($message);
        }
    }

    abstract public function update(PipeMessageInterface $config): void;

    protected function shareMessageToWorkers(PipeMessageInterface $message): void
    {
        $server = ApplicationContext::getContainer()->get(Server::class);
        $workerCount = $server->setting['worker_num'] + ($server->setting['task_worker_num'] ?? 0) - 1;
        if ($workerCount > 0) {
            for ($workerId = 0; $workerId <= $workerCount; ++$workerId) {
                if ($server->worker_id != $workerId) {
                    $server->sendMessage($message, $workerId);
                } else {
                    $this->update($message);
                }
            }
        }
    }

    protected function shareMessageToUserProcesses(PipeMessageInterface $message): void
    {
        $processes = ProcessCollector::all();
        if ($processes) {
            $string = serialize($message);
            /** @var Process $process */
            foreach ($processes as $process) {
                $result = $process->exportSocket()->send($string, 10);
                if ($result === false) {
                    $this->getLogger()->error('Configuration synchronization failed. Please restart the server.');
                }
            }
        }
    }
}
