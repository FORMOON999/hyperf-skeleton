<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\Model\ClassInfo;

class GeneratorListSearch extends BaseGeneratorRequest
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ListSearch';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Request/ListSearch.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name);
        return $stub;
    }
}