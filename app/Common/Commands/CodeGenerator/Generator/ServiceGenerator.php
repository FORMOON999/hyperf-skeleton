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
        $error = $results['error'];
        $serviceInterface = $results['serviceInterface'];
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceUses($stub, [
                $serviceInterface->namespace,
                $error->namespace,
                $this->modelInfo->namespace,
                $this->modelInfo->namespace . 'Entity',
            ])
            ->replaceInheritance($stub, $serviceInterface->name)
            ->replace($stub, '%MODEL_NAME%', $this->modelInfo->name)
            ->replace($stub, '%MODEL_NAME_ENTITY%', $this->modelInfo->name . 'Entity')
            ->replace($stub, '%ERROR%', $error->name);
        return $stub;
    }
}
