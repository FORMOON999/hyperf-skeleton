<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Response;

use App\Common\Commands\CodeGenerator\Generator\ApplicationGenerator;

abstract class BaseGeneratorResponse extends ApplicationGenerator
{
    public function getPath(string $module = ''): string
    {
        $version = ucfirst($this->config->version);
        return parent::getPath("/Entity/Response/{$module}/{$version}/{$this->modelInfo->name}");
    }

    public function isWrite(): bool
    {
        return !$this->modelInfo->exist;
    }
}