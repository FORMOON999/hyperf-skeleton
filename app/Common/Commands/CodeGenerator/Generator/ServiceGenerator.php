<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;

class ServiceGenerator extends AbstractGenerator
{
    public function getPath(string $module = ''): string
    {
        return parent::getPath('/Service');
    }

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Service';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/Service.stub');
        $daoInterface = $results['daoInterface'];
        $error = $results['error'];
        $serviceInterface = $results['serviceInterface'];
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceUses($stub, [
                $daoInterface->namespace,
                $serviceInterface->namespace,
                $error->namespace
            ])
            ->replaceInheritance($stub, $serviceInterface->name)
            ->replace($stub, '%DAO_INTERFACE%', $daoInterface->name)
            ->replace($stub, '%DAO_NAME%', str_replace('Interface', '', lcfirst($daoInterface->name)))
            ->replace($stub, '%ERROR%', $error->name);
        return $stub;
    }
}