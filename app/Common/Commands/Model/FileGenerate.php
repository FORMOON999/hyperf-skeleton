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

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Hyperf\Utils\Str;
use Lengbin\Common\BaseObject;
use Lengbin\Helper\YiiSoft\StringHelper;
use Lengbin\PhpGenerator\GenerateClass;
use Lengbin\PhpGenerator\Printer\PrinterFactory;
use Lengbin\PhpGenerator\Property;

class FileGenerate
{
    protected GenerateClass $generate;

    protected array $exceptColumn = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function __construct(
        public ModelInfo $modelInfo,
        public ClassInfo $classInfo,
        public bool $required = false,
        public bool $all = false,
    ) {
        $generate = new GenerateClass();
        $generate->setVersion(PrinterFactory::VERSION_PHP80);
        $generate->setStrictTypes();
        $generate->setClassname($this->classInfo->name);
        $generate->setNamespace(StringHelper::dirname($this->classInfo->namespace));
        $generate->setInheritance('\\' . BaseObject::class);
        $generate->setUses([
            ApiModelProperty::class,
        ]);

        if ($this->required) {
            $generate->addUse(Required::class);
        }
        $this->generate = $generate;
    }

    public function getGenerateClass(): GenerateClass
    {
        return $this->generate;
    }

    public function handle(): string
    {
        foreach ($this->modelInfo->columns as $column) {
            if (! $this->all && (in_array($column['column_name'], [$this->modelInfo->pk]) || in_array($column['column_name'], $this->exceptColumn))) {
                continue;
            }
            [$name, $type, $comment] = $this->getProperty($column);
            $required = $this->required ? ', Required' : '';
            $this->generate->addProperty(new Property([
                'name' => $name,
                'type' => $type,
                'annotation' => true,
                'comments' => [
                    '#[ApiModelProperty("' . $comment . '")' . $required . ']',
                ],
            ]));
        }
        return $this->generate->__toString();
    }

    public function pk(): string
    {
        foreach ($this->modelInfo->columns as $column) {
            if ($column['column_name'] == $this->modelInfo->pk) {
                [$name, $type, $comment] = $this->getProperty($column);
                if (empty($comment)) {
                    $comment = $this->modelInfo->comment . 'ID';
                }
                $this->generate->addProperty(new Property([
                    'name' => $name,
                    'type' => $type,
                    'annotation' => true,
                    'comments' => [
                        '#[ApiModelProperty("' . $comment . '"), Required]',
                    ],
                ]));
                break;
            }
        }
        return $this->generate->__toString();
    }

    protected function getProperty($column): array
    {
        $name = Str::camel($column['column_name']);

        $type = $this->formatPropertyType($column['data_type'], $column['cast'] ?? null);

        $comment = $column['column_comment'] ?? '';

        return [$name, $type, $comment];
    }

    protected function formatDatabaseType(string $type): ?string
    {
        switch ($type) {
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'int':
                return 'integer';
            case 'bigint':
            case 'decimal':
            case 'float':
            case 'double':
            case 'real':
                return 'string';
            case 'bool':
            case 'boolean':
                return 'boolean';
            default:
                return null;
        }
    }

    protected function formatPropertyType(string $type, ?string $cast): ?string
    {
        if (! isset($cast)) {
            $cast = $this->formatDatabaseType($type) ?? 'string';
        }

        switch ($cast) {
            case 'integer':
            case 'version':
                return 'int';
            case 'date':
            case 'datetime':
                return $type;
            case 'json':
                return 'array';
        }

        return $cast;
    }
}
