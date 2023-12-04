<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator\Response;

use App\Common\Commands\CodeGenerator\ClassInfo;
use App\Common\Commands\CodeGenerator\FileGenerate;

class GeneratorListItem extends BaseGeneratorResponse
{

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Item';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $fileGenerate = new FileGenerate($this->modelInfo, $class, false, true);
        return $fileGenerate->handle();
    }
}