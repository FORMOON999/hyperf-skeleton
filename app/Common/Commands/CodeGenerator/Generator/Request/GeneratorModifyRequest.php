<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\ClassInfo;

class GeneratorModifyRequest extends BaseGeneratorRequest
{
    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ModifyRequest';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Request/ModifyRequest.stub');
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replace($stub, '%DATA%', $results['requestModifyData']->name)
            ->replace($stub, '%SEARCH%', $results['requestSearch']->name)
            ->replace($stub, '%CONDITION%', $results['requestCondition']->name);
        return $stub;
    }
}