<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\CodeGenerator\ModelInfo;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Commands\ModelOption;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Utils\CodeGen\Project;
use Hyperf\Utils\Str;
use App\Common\Commands\Model\ModelCommand;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Output\NullOutput;

class ModelGenerator extends ModelCommand
{

    protected bool $ddd;

    public function __construct(ContainerInterface $container, bool $ddd)
    {
        parent::__construct($container);
        $this->ddd = $ddd;
        $this->resolver = $this->container->get(ConnectionResolverInterface::class);
        $this->config = $this->container->get(ConfigInterface::class);
        $this->astParser = (new ParserFactory())->create(ParserFactory::ONLY_PHP7);
        $this->printer = new Standard();
        $this->output = new NullOutput();
    }

    protected function getOption(string $name, string $key, string $pool = 'default', $default = null)
    {
        return $this->config->get("databases.{$pool}.{$key}", $default);
    }

    protected function getOptionPath(string $table, ModelOption $option): string
    {
        $isOpenDdd = $this->ddd;
        if (!$isOpenDdd) {
            return $option->getPath();
        }
        $module = ucwords(Str::before($table, '_'));
        $paths = explode('/', $option->getPath());
        array_splice($paths, 1, 0, [$module]);
        return implode('/', $paths);
    }

    protected function getModelOption(string $pool): ModelOption
    {
        $option = new ModelOption();
        if ($this->ddd) {
            $path = 'app/Repository/Model';
        } else {
            $path = $this->getOption('path', 'commands.gen:model.path', $pool, 'app/Model');
        }
        $option->setPool($pool)
            ->setPath($path)
            ->setPrefix($this->getOption('prefix', 'prefix', $pool, ''))
            ->setInheritance($this->getOption('inheritance', 'commands.gen:model.inheritance', $pool, 'Model'))
            ->setUses($this->getOption('uses', 'commands.gen:model.uses', $pool, 'Hyperf\DbConnection\Model\Model'))
            ->setForceCasts($this->getOption('force-casts', 'commands.gen:model.force_casts', $pool, false))
            ->setRefreshFillable($this->getOption('refresh-fillable', 'commands.gen:model.refresh_fillable', $pool, false))
            ->setTableMapping($this->getOption('table-mapping', 'commands.gen:model.table_mapping', $pool, []))
            ->setIgnoreTables($this->getOption('ignore-tables', 'commands.gen:model.ignore_tables', $pool, []))
            ->setWithComments($this->getOption('with-comments', 'commands.gen:model.with_comments', $pool, false))
            ->setWithIde($this->getOption('with-ide', 'commands.gen:model.with_ide', $pool, false))
            ->setVisitors($this->getOption('visitors', 'commands.gen:model.visitors', $pool, []))
            ->setPropertyCase($this->getOption('property-case', 'commands.gen:model.property_case', $pool));
        return $option;
    }

    public function check(): bool
    {
        return false;
    }

    protected function getTables(ModelOption $option, ?string $table): array
    {
        $tables = [];
        if (!empty($table)) {
            $tables = [$table];
        } else {
            $builder = $this->getSchemaBuilder($option->getPool());
            foreach ($builder->getAllTables() as $row) {
                $row = (array)$row;
                $table = reset($row);
                if (!$this->isIgnoreTable($table, $option)) {
                    $tables[] = $table;
                }
            }
        }
        return $tables;
    }

    public function generate(string $pool, ?string $table, ?bool $force): array
    {
        $tableClass = [];

        $option = $this->getModelOption($pool);
        $tables = $this->getTables($option, $table);
        $project = new Project();
        $builder = $this->getSchemaBuilder($option->getPool());

        foreach ($tables as $table) {
            $classInfo = new ModelInfo();

            $sql = "select TABLE_COMMENT from information_schema.tables where table_name = '%s' and table_schema = '%s';";
            $comment = $builder->getConnection()->selectOne(sprintf($sql, $table, $builder->getConnection()->getDatabaseName()));
            $classInfo->comment = $comment->TABLE_COMMENT;

            $table = Str::replaceFirst($option->getPrefix(), '', $table);
            $optionPath = $this->getOptionPath($table, $option);
            $classInfo->module = $this->ddd ? ucwords(Str::before($table, '_')) : '';

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

            $this->createModel($table, $option);
            $tableClass[] = $classInfo;
        }
        return $tableClass;
    }
}