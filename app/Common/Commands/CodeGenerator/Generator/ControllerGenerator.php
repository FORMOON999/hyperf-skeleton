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
use App\Common\Helpers\Arrays\ArrayHelper;
use App\Common\Helpers\VarDumper;
use Hyperf\Stringable\Str;

class ControllerGenerator extends ApplicationGenerator
{
    public function getPath(string $module = ''): string
    {
        $version = ucfirst($this->config->version);
        $path = $this->ddd ? 'Application' : 'Controller';
        return parent::getPath("/{$path}/{$module}/{$version}/{$this->modelInfo->name}");
    }

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Controller';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/Controller.stub');
        $error = $results['error'];
        $serviceInterface = $results['serviceInterface'];
        $application = $results['_application'];
        $url = array_merge(
            explode('/', $this->config->url),
            [
                lcfirst($this->config->version),
                $application != "api" ? $application : '',
            ],
            explode('/', Str::snake($this->modelInfo->name, '/'))
        );
        $uri = implode('/', array_filter($url));

        $requestList = $results['requestList'];
        $responseList = $results['responseList'];
        $requestCreate = $results['requestCreate'];
        $requestDetail = $results['requestDetail'];
        $responseDetail = $results['responseDetail'];
        $requestModify = $results['requestModify'];
        $requestRemove = $results['requestRemove'];

        $filed = ArrayHelper::get($this->modelInfo->columns, '*.column_name');
        $key = array_search('deleted_at', $filed);
        if ($key !== false) {
            unset($filed[$key]);
            $filed = array_values($filed);
        }

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
            ])
            ->replace($stub, '%SERVICE%', $serviceInterface->name)
            ->replace($stub, '%SERVICE_NAME%', str_replace('Interface', '', lcfirst($serviceInterface->name)))
            ->replace($stub, '%Middleware%', ucfirst($application))
            ->replace($stub, '%URI%', $uri)
            ->replace($stub, '%TITLE%', ucfirst($application) . '/' . $this->modelInfo->comment)
            ->replace($stub, '%MESSAGE%', $this->modelInfo->comment)
            ->replace($stub, '%LIST_REQUEST%', $requestList->name)
            ->replace($stub, '%LIST_RESPONSE%', $responseList->name)
            ->replace($stub, '%CREAT_REQUEST%', $requestCreate->name)
            ->replace($stub, '%MODIFY_REQUEST%', $requestModify->name)
            ->replace($stub, '%DETAIL_REQUEST%', $requestDetail->name)
            ->replace($stub, '%DETAIL_RESPONSE%', $responseDetail->name)
            ->replace($stub, '%REMOVE_REQUEST%', $requestRemove->name)
            ->replace($stub, '%ERROR%', $error->name)
            ->replace($stub, '%FILED%', VarDumper::export($filed));
        return $stub;
    }
}
