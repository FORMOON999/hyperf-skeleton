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

use App\Common\Commands\CodeGenerator\GeneratorConfig;
use App\Common\Commands\Model\ClassInfo;
use App\Common\Commands\Model\ModelInfo;
use App\Common\Core\BaseObject;
use Hyperf\CodeParser\Project;
use Hyperf\Dag\Runner;

abstract class AbstractGenerator extends BaseObject implements Runner
{
    protected Project $project;

    protected ModelInfo $modelInfo;

    protected GeneratorConfig $config;

    protected bool $ddd;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        $this->project = new Project();
    }

    abstract public function getFilename(): string;

    abstract public function buildClass(ClassInfo $class, array $results = []): string;

    public function getPath(string $module = ''): string
    {
        $dirs = array_filter(array_merge([
            $this->config->path,
            $this->modelInfo->module,
        ], explode('/', $module)));
        return implode('/', $dirs);
    }

    public function run(array $results = [])
    {
        $class = $this->getClassInfo();
        if (! file_exists($class->file)) {
            $this->mkdir($class->file);
            $content = $this->buildClass($class, $results);
            if (! empty($content)) {
                file_put_contents($class->file, $content);
            }
        }
        return $class;
    }

    protected function getClassInfo(string $application = ''): ClassInfo
    {
        $class = $this->project->namespace($this->getPath(ucfirst($application))) . $this->getFilename();
        $path = BASE_PATH . '/' . $this->project->path($class);
        return new ClassInfo([
            'name' => $this->getFilename(),
            'namespace' => $class,
            'file' => $path,
        ]);
    }

    protected function replace(string &$stub, string $name, string $value): static
    {
        $stub = str_replace(
            [$name],
            [$value],
            $stub
        );
        return $this;
    }

    /**
     * Get the full namespace for a given class, without the class name.
     */
    protected function getNamespace(string $name): string
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Replace the namespace for the given stub.
     */
    protected function replaceNamespace(string &$stub, string $name): static
    {
        $stub = str_replace(
            ['%NAMESPACE%'],
            [$this->getNamespace($name)],
            $stub
        );

        return $this;
    }

    protected function replaceInheritance(string &$stub, string $inheritance): static
    {
        $stub = str_replace(
            ['%INHERITANCE%'],
            [$inheritance],
            $stub
        );

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     */
    protected function replaceClass(string &$stub, string $name): static
    {
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $stub = str_replace('%CLASS%', $class, $stub);

        return $this;
    }

    protected function replaceUses(string &$stub, array $uses): static
    {
        $str = '';
        if (! empty($uses)) {
            foreach ($uses as $use) {
                $str .= "use {$use};\n";
            }
        }

        $stub = str_replace(
            ['%USES%'],
            [$str],
            $stub
        );

        return $this;
    }

    protected function mkdir(string $path): void
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }
}
