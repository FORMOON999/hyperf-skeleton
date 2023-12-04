<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\Generator\ApplicationGenerator;

abstract class BaseGeneratorRequest extends ApplicationGenerator
{
    public function getPath(string $module = ''): string
    {
        $version = ucfirst($this->config->version);
        return parent::getPath("/Entity/Request/{$module}/{$version}/{$this->modelInfo->name}");
    }

    public function isWrite(): bool
    {
        return !$this->modelInfo->exist;
    }
}