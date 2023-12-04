<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;

class DaoInterfaceGenerator extends AbstractGenerator
{
    public function getPath(string $module = ''): string
    {
        return parent::getPath('/Repository/Dao/Contract');
    }

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'DaoInterface';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/DaoInterface.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name);
        return $stub;
    }
}