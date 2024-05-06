<?php

declare(strict_types=1);

namespace App\Common\Core;

interface ArrayableInterface
{
    public function toArray(): array;
}
