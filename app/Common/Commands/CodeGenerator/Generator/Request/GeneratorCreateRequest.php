<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\ClassInfo;

class GeneratorCreateRequest extends BaseGeneratorRequest
{
    public function getFilename(): string
    {
        return $this->modelInfo->name . 'CreateRequest';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Request/CreateRequest.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replace($stub, '%CONDITION%', $results['requestCondition']->name)
            ->replace($stub, '%SEARCH%', $results['requestSearch']->name)
            ->replace($stub, '%DATA%', $results['requestCreateData']->name);
        return $stub;
    }
}