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

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use Lengbin\Helper\YiiSoft\VarDumper;

class LogicGenerator extends ApplicationGenerator
{
    public function getPath(string $module = ''): string
    {
        $version = ucfirst($this->config->version);
        return parent::getPath("/Logic/{$module}/{$version}");
    }

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Logic';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/Logic.stub');
        $error = $results['error'];
        $serviceInterface = $results['serviceInterface'];
        $requestList = $results['requestList'];
        $responseList = $results['responseList'];
        $requestCreate = $results['requestCreate'];
        $requestDetail = $results['requestDetail'];
        $responseDetail = $results['responseDetail'];
        $requestModify = $results['requestModify'];
        $requestRemove = $results['requestRemove'];
        $filed = ArrayHelper::get($this->modelInfo->columns, '*.column_name');

        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceUses($stub, [
                $error->namespace,
                $serviceInterface->namespace,
                $requestList->namespace,
                $responseList->namespace,
                $requestCreate->namespace,
                $requestDetail->namespace,
                $responseDetail->namespace,
                $requestModify->namespace,
                $requestRemove->namespace,
                $this->modelInfo->namespace . 'Entity',
            ])
            ->replace($stub, '%SERVICE%', $serviceInterface->name)
            ->replace($stub, '%SERVICE_NAME%', str_replace('Interface', '', lcfirst($serviceInterface->name)))
            ->replace($stub, '%LIST_REQUEST%', $requestList->name)
            ->replace($stub, '%LIST_RESPONSE%', $responseList->name)
            ->replace($stub, '%CREAT_REQUEST%', $requestCreate->name)
            ->replace($stub, '%MODIFY_REQUEST%', $requestModify->name)
            ->replace($stub, '%DETAIL_REQUEST%', $requestDetail->name)
            ->replace($stub, '%DETAIL_RESPONSE%', $responseDetail->name)
            ->replace($stub, '%REMOVE_REQUEST%', $requestRemove->name)
            ->replace($stub, '%ERROR%', $error->name)
            ->replace($stub, '%FILED%', VarDumper::export($filed))
            ->replace($stub, '%MODEL_NAME_ENTITY%', $this->modelInfo->name . 'Entity');
        return $stub;
    }
}
