<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\ClassInfo;
use App\Common\Commands\CodeGenerator\FileGenerate;

class GeneratorSearch extends BaseGeneratorRequest
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Search';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $fileGenerate = new FileGenerate($this->modelInfo, $class, true, false, true);
        return $fileGenerate->pk();
    }
}