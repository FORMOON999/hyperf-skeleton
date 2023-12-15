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

namespace App\Common\Commands\CodeGenerator\Generator\Response;

use App\Common\Commands\Model\ClassInfo;

class GeneratorDetailResponse extends BaseGeneratorResponse
{
    public function getFilename(): string
    {
        return $this->modelInfo->name . 'DetailResponse';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Response/DetailResponse.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceUses($stub, [
                $this->modelInfo->namespace . 'Entity',
            ])
            ->replace($stub, '%INHERITANCE%', $this->modelInfo->name . 'Entity');
        return $stub;
    }
}
