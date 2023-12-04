<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\ClassInfo;

class GeneratorListRequest extends BaseGeneratorRequest
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ListRequest';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Request/ListRequest.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replace($stub, '%CONDITION%', $results['requestCondition']->name)
            ->replace($stub, '%SEARCH%', $results['requestListSearch']->name);
        return $stub;
    }
}