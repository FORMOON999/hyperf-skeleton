<?php

namespace App\Common\Util\IPC;

interface PipeMessageInterface
{
    public function getData(): array;
}