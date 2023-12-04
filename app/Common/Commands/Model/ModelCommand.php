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

namespace App\Common\Commands\Model;

use App\Common\Core\Entity\BaseModelEntity;
use Hyperf\CodeParser\Project;
use Hyperf\Database\Commands\Ast\ModelRewriteConnectionVisitor;
use Hyperf\Database\Commands\ModelCommand as BaseModelCommand;
use Hyperf\Database\Commands\ModelData;
use Hyperf\Database\Commands\ModelOption;
use Hyperf\Database\Schema\Builder;
use Hyperf\Stringable\Str;
use Lengbin\Helper\YiiSoft\StringHelper;
use PhpParser\NodeTraverser;

class ModelCommand extends BaseModelCommand
{
    protected function getPrimaryKey(array $columns): string
    {
        $primaryKey = 'id';
        foreach ($columns as $column) {
            if ($column['column_key'] === 'PRI') {
                $primaryKey = $column['column_name'];
                break;
            }
        }
        return $primaryKey;
    }

    protected function getOptionPath(string $table, ModelOption $option): string
    {
        $isOpenDdd = $this->getOption('path', 'commands.gen:model.for_table_ddd', $option->getPool(), false);
        if (! $isOpenDdd) {
            return $option->getPath();
        }
        $module = ucwords(Str::before($table, '_'));
        $paths = explode('/', $option->getPath());
        array_splice($paths, 1, 0, [$module]);
        return implode('/', $paths);
    }

    protected function getModelInfo(Project $project, Builder $builder, string $table, ModelOption $option, bool $force = false, bool $ddd = false): ModelInfo
    {
        $classInfo = new ModelInfo();

        $sql = "select TABLE_COMMENT from information_schema.tables where table_name = '%s' and table_schema = '%s';";
        $comment = $builder->getConnection()->selectOne(sprintf($sql, $option->getPrefix() . $table, $builder->getConnection()->getDatabaseName()));
        $classInfo->comment = $comment->TABLE_COMMENT;

        $table = Str::replaceFirst($option->getPrefix(), '', $table);
        $optionPath = $this->getOptionPath($table, $option);
        $classInfo->module = $ddd ? ucwords(Str::before($table, '_')) : '';

        $columns = $this->formatColumns($builder->getColumnTypeListing($table));
        $classInfo->columns = $columns;
        $classInfo->pk = $this->getPrimaryKey($columns);

        $class = $option->getTableMapping()[$table] ?? Str::studly(Str::singular($table));
        $classInfo->name = $class;

        $class = $project->namespace($optionPath) . $class;
        $classInfo->namespace = $class;

        if ($force) {
            $classInfo->exist = false;
        } else {
            $file = BASE_PATH . '/' . $project->path($class);
            $classInfo->exist = file_exists($file);
        }

        return $classInfo;
    }

    protected function createModel(string $table, ModelOption $option)
    {
        $builder = $this->getSchemaBuilder($option->getPool());
        $table = Str::replaceFirst($option->getPrefix(), '', $table);
        $optionPath = $this->getOptionPath($table, $option);

        $columns = $this->formatColumns($builder->getColumnTypeListing($table));
        $project = new Project();
        $class = $option->getTableMapping()[$table] ?? Str::studly(Str::singular($table));
        $class = $project->namespace($optionPath) . $class;
        $path = BASE_PATH . '/' . $project->path($class);

        if (! file_exists($path)) {
            $this->mkdir($path);
            file_put_contents($path, $this->buildClassPrimaryKey($table, $class, $this->getPrimaryKey($columns), $option));
        }

        $columns = $this->getColumns($class, $columns, $option->isForceCasts());
        foreach ($columns as $key => $value) {
            $columns[$key]['cast'] = $casts[$value['column_name']] ?? null;
            if ($value['column_name'] === 'version') {
                $value['cast'] = 'version';
                $columns[$key] = $value;
            }
        }

        $entityClass = $class . 'Entity';
        $entityPath = BASE_PATH . '/' . $project->path($entityClass);
        if (! file_exists($entityPath)) {
            $this->mkdir($entityPath);
            $modelInfo = $this->getModelInfo($project, $builder, $table, $option);
            $classInfo = new ClassInfo([
                'name' => StringHelper::basename($entityClass),
                'namespace' => $entityClass,
            ]);
            $fileGenerate = new FileGenerate($modelInfo, $classInfo);
            $generate = $fileGenerate->getGenerateClass();
            $generate->setInheritance('\\' . BaseModelEntity::class);
            file_put_contents($entityPath, $fileGenerate->handle());
            $this->output->writeln(sprintf('<info>Model %s was created.</info>', $entityClass));
        }

        $stms = $this->astParser->parse(file_get_contents($path));
        $traverser = new NodeTraverser();
        $traverser->addVisitor(\Hyperf\Support\make(ModelUpdateVisitor::class, [
            'class' => $class,
            'columns' => $columns,
            'option' => $option,
        ]));
        $traverser->addVisitor(\Hyperf\Support\make(ModelRewriteConnectionVisitor::class, [$class, $option->getPool()]));
        $data = \Hyperf\Support\make(ModelData::class, ['class' => $class, 'columns' => $columns]);
        foreach ($option->getVisitors() as $visitorClass) {
            $traverser->addVisitor(\Hyperf\Support\make($visitorClass, [$option, $data]));
        }
        $stms = $traverser->traverse($stms);
        $code = $this->printer->prettyPrintFile($stms);

        file_put_contents($path, $code);
        $this->output->writeln(sprintf('<info>Model %s was created.</info>', $class));

        if ($option->isWithIde()) {
            $this->generateIDE($code, $option, $data);
        }
    }

    protected function replacePrimaryKey(string &$stub, string $primaryKey): self
    {
        $stub = str_replace(['%PRIMARY_KEY%'], [$primaryKey], $stub);

        return $this;
    }

    /**
     * Build the class with the given name.
     */
    protected function buildClassPrimaryKey(string $table, string $name, string $primaryKey, ModelOption $option): string
    {
        $stub = file_get_contents(__DIR__ . '/stubs/Model.stub');

        return $this->replaceNamespace($stub, $name)
            ->replaceInheritance($stub, $option->getInheritance())
            ->replaceConnection($stub, $option->getPool())
            ->replaceUses($stub, $option->getUses())
            ->replaceClass($stub, $name)
            ->replacePrimaryKey($stub, $primaryKey)
            ->replaceTable($stub, $table);
    }
}
