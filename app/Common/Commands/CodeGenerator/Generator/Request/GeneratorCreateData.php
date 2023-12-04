<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\Model\ClassInfo;
use App\Common\Commands\Model\FileGenerate;

class GeneratorCreateData extends BaseGeneratorRequest
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'CreateData';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $fileGenerate = new FileGenerate($this->modelInfo, $class, true);
        return $fileGenerate->handle();
    }
}