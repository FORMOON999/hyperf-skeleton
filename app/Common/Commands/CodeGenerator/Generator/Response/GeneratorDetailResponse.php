<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Response;

use App\Common\Commands\CodeGenerator\ClassInfo;

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
            ->replaceInheritance($stub, $results['responseListItem']->name);
        return $stub;
    }
}