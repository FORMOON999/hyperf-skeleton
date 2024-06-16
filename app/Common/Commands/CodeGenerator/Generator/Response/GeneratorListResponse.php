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

namespace App\Common\Commands\CodeGenerator\Generator\Response;

use App\Common\Commands\Model\ClassInfo;
use App\Common\Core\Annotation\ArrayType;

class GeneratorListResponse extends BaseGeneratorResponse
{
    public function getFilename(): string
    {
        return $this->modelInfo->name . 'ListResponse';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__, 2) . '/stubs/Response/ListResponse.stub');
        $responseDetail = $results['responseDetail'];
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceUses($stub, [
                'App\Common\Core\Entity\BaseListResponse',
                'Hyperf\ApiDocs\Annotation\ApiModelProperty',
                ArrayType::class,
            ])
            ->replace($stub, '%NAME%', $responseDetail->name);
        return $stub;
    }
}
