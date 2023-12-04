<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator;

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
        'create_at',
        'update_at',
    ];

    public function __construct(
        public ModelInfo $modelInfo,
        public ClassInfo $classInfo,
        public bool      $required = false,
        public bool      $all = false,
        public bool      $enable = false
    )
    {
        $generate = new GenerateClass();
        $generate->setVersion(PrinterFactory::VERSION_PHP80);
        $generate->setStrictTypes();
        $generate->setClassname($this->classInfo->name);
        $generate->setNamespace(StringHelper::dirname($this->classInfo->namespace));
        $generate->setInheritance('BaseObject');
        $generate->setUses([
            BaseObject::class,
            ApiModelProperty::class,
        ]);

        if ($this->enable) {
            $generate->addUse(EnableIdentifier::class);
            $generate->addTrait('EnableIdentifier');
        }

        if ($this->required) {
            $generate->addUse(Required::class);
        }
        $this->generate = $generate;
    }

    public function handle(): string
    {
        foreach ($this->modelInfo->columns as $column) {
            if (!$this->all && (in_array($column['column_name'], [$this->modelInfo->pk, 'enable']) || in_array($column['column_name'], $this->exceptColumn))) {
                continue;
            }
            if ($this->all && $column['column_name'] == 'enable') {
                continue;
            }
            [$name, $type, $comment] = $this->getProperty($column);
            $required = $this->required ? ', Required' : '';
            $this->generate->addProperty(new Property([
                'name' => $name,
                'type' => $type,
                'annotation' => true,
                'comments' => [
                    '#[ApiModelProperty("' . $comment . '")' . $required . ']'
                ]
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
                        '#[ApiModelProperty("' . $comment . '"), Required]'
                    ]
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
        if (!isset($cast)) {
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