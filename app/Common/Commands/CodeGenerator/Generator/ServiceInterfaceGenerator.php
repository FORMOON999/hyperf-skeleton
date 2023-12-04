<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;

class ServiceInterfaceGenerator extends AbstractGenerator
{
    public function getPath(string $module = ''): string
    {
        $dirs = array_filter(array_merge([
            $this->config->path,
            'Infrastructure',
            $this->modelInfo->module,
        ]));
        return implode('/', $dirs);
    }

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Interface';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/ServiceInterface.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name);
        return $stub;
    }
}