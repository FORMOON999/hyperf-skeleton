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
            ->replaceUses($stub, [
                $this->modelInfo->namespace . 'Entity',
                Output::class,
            ])
            ->replace($stub, '%MODEL_NAME_ENTITY%', $this->modelInfo->name . 'Entity')
            ->replaceClass($stub, $class->name);
        return $stub;
    }
}
