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
use App\Common\Commands\Model\FileGenerate;

class GeneratorModifyRequest extends BaseGeneratorRequest
{
    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ModifyRequest';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $fileGenerate = new FileGenerate($this->modelInfo, $class);
        return $fileGenerate->handle();
    }
}
