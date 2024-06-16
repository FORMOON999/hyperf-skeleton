<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\Model\ClassInfo;
use App\Common\Commands\Model\FileGenerate;

class GeneratorRemoveRequest extends BaseGeneratorRequest
{
    public function getFilename(): string
    {
        return $this->modelInfo->name . 'RemoveRequest';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        if ($results['_mode'] == 'reset') {
            return '';
        }
        $fileGenerate = new FileGenerate($this->modelInfo, $class, true, false);
        return $fileGenerate->pk();
    }
}