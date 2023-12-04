<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\CodeGenerator\ClassInfo;
use Hyperf\Utils\Str;

class ConstantControllerGenerator extends ApplicationGenerator
{
    public function getPath(string $module = ''): string
    {
        $version = ucfirst($this->config->version);
        $path = $this->ddd ? 'Application' : 'Controller';
        return parent::getPath("/{$path}/{$module}/{$version}");
    }

    public function getFilename(): string
    {
        return $this->modelInfo->module . 'ConstantController';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/ConstantController.stub');
        $application = $results['_application'];
        $url = array_merge(
            explode('/', $this->config->url), [
            lcfirst($this->config->version),
            $application
        ],
            explode('/', Str::snake($this->modelInfo->name, '/'))
        );
        $uri = implode('/', array_filter($url)) . '/constant';

        $path = implode('/', array_filter([
            $this->config->path,
            $this->modelInfo->module,
            "Constants"
        ]));

        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replace($stub, '%Middleware%', ucfirst($application))
            ->replace($stub, '%URI%', $uri)
            ->replace($stub, '%PATH%', $path)
            ->replace($stub, '%TITLE%', ucfirst($application) . '/' . $this->modelInfo->comment)
            ->replace($stub, '%MESSAGE%', $this->modelInfo->comment);
        return $stub;
    }
}