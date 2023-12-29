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
use App\Common\Core\Entity\Output;

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
        $serviceInterface = $results['serviceInterface'];
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceUses($stub, [
                $serviceInterface->namespace,
                $this->modelInfo->namespace,
                $this->modelInfo->namespace . 'Entity',
                Output::class,
            ])
            ->replaceInheritance($stub, $serviceInterface->name)
            ->replace($stub, '%MODEL%', $this->modelInfo->name)
            ->replace($stub, '%MODEL_NAME%', lcfirst($this->modelInfo->name))
            ->replace($stub, '%MODEL_NAME_ENTITY%', $this->modelInfo->name . 'Entity');
        return $stub;
    }
}
