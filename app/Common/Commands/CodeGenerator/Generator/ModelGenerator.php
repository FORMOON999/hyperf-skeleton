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

use App\Common\Commands\Model\ModelCommand;
use Hyperf\CodeParser\Project;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Commands\ModelOption;
use Hyperf\Database\ConnectionResolverInterface;
use Hyperf\Utils\Str;
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

    public function check(): bool
    {
        return false;
    }

    public function generate(string $pool, ?string $table, ?bool $force): array
    {
        $tableClass = [];

        $option = $this->getModelOption($pool);
        $tables = $this->getTables($option, $table);
        $project = new Project();
        $builder = $this->getSchemaBuilder($option->getPool());

        foreach ($tables as $table) {
            $classInfo = $this->getModelInfo($project, $builder, $table, $option, $force, $this->ddd);

            $this->createModel($table, $option);
            $tableClass[] = $classInfo;
        }
        return $tableClass;
    }

    protected function getOption(string $name, string $key, string $pool = 'default', $default = null)
    {
        return $this->config->get("databases.{$pool}.{$key}", $default);
    }

    protected function getOptionPath(string $table, ModelOption $option): string
    {
        $isOpenDdd = $this->ddd;
        if (! $isOpenDdd) {
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

    protected function isIgnoreTable(string $table, ModelOption $option): bool
    {
        $table = Str::replaceFirst($option->getPrefix(), '', $table);
        return parent::isIgnoreTable($table, $option);
    }

    protected function getTables(ModelOption $option, ?string $table): array
    {
        $tables = [];
        if (! empty($table)) {
            $tables = [$table];
        } else {
            $builder = $this->getSchemaBuilder($option->getPool());
            foreach ($builder->getAllTables() as $row) {
                $row = (array) $row;
                $table = reset($row);
                if (! $this->isIgnoreTable($table, $option)) {
                    $tables[] = $table;
                }
            }
        }
        return $tables;
    }
}
