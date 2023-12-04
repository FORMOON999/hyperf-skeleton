<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Response;

use App\Common\Commands\CodeGenerator\ClassInfo;

class GeneratorListResponse extends BaseGeneratorResponse
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ListResponse';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Response/ListResponse.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replace($stub, '%ITEM%', $results['responseListItem']->name);
        return $stub;
    }
}