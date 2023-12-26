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

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\Model\ClassInfo;

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
            ->replaceUses($stub, [
                'Hyperf\\ApiDocs\\Annotation\\ApiModelProperty',
                'Hyperf\\DTO\\Annotation\\Validation\\Required',
                'Lengbin\\Common\\BaseObject',
                $this->modelInfo->namespace . 'Entity',
            ])
            ->replace($stub, '%DATA%', $this->modelInfo->name . 'Entity')
            ->replace($stub, '%SEARCH%', $results['requestSearch']->name);
        return $stub;
    }
}
