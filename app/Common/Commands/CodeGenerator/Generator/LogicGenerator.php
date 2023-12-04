<?php

declare(strict_types=1);

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
        $service = $results['service'];
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
                $service->namespace,
                $requestList->namespace,
                $responseList->namespace,
                $requestCreate->namespace,
                $requestDetail->namespace,
                $responseDetail->namespace,
                $requestModify->namespace,
                $requestRemove->namespace,
            ])
            ->replace($stub, '%SERVICE%', $service->name)
            ->replace($stub, '%SERVICE_NAME%', lcfirst($service->name))
            ->replace($stub, '%LIST_REQUEST%', $requestList->name)
            ->replace($stub, '%LIST_RESPONSE%', $responseList->name)
            ->replace($stub, '%CREAT_REQUEST%', $requestCreate->name)
            ->replace($stub, '%MODIFY_REQUEST%', $requestModify->name)
            ->replace($stub, '%DETAIL_REQUEST%', $requestDetail->name)
            ->replace($stub, '%DETAIL_RESPONSE%', $responseDetail->name)
            ->replace($stub, '%REMOVE_REQUEST%', $requestRemove->name)
            ->replace($stub, '%FILED%', VarDumper::export($filed));
        return $stub;
    }
}