<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\ClassInfo;
use App\Common\Commands\CodeGenerator\FileGenerate;

class GeneratorModifyData extends BaseGeneratorRequest
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ModifyData';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $fileGenerate = new FileGenerate($this->modelInfo, $class);
        return $fileGenerate->handle();
    }
}