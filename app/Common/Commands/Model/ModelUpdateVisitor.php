<?php
/**
 * Created by PhpStorm.
 * Date:  2021/9/2
 * Time:  2:36 下午
 */

declare(strict_types=1);

namespace App\Common\Commands\Model;

use PhpParser\Node;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\PropertyProperty;

class ModelUpdateVisitor extends \Hyperf\Database\Commands\Ast\ModelUpdateVisitor
{

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

    protected function rewriteCasts(Node\Stmt\PropertyProperty $node): Node\Stmt\PropertyProperty
    {
        $items = [];
        $keys = [];
        if ($node->default instanceof Node\Expr\Array_) {
            $items = $node->default->items;
        }

        if ($this->option->isForceCasts()) {
            $items = [];
            $casts = $this->class->getCasts();
            foreach ($node->default->items as $item) {
                $caster = $casts[$item->key->value] ?? null;
                if ($caster && $this->isCaster($caster)) {
                    $items[] = $item;
                }
            }
        }

        foreach ($items as $item) {
            $keys[] = $item->key->value;
        }
        foreach ($this->columns as $column) {
            $name = $column['column_name'];
            $type = $column['cast'] ?? null;
            if ($type === 'version') {
                $type = 'string';
            }
            if (in_array($name, $keys)) {
                continue;
            }
            if ($type || $type = $this->formatDatabaseType($column['data_type'])) {
                $items[] = new Node\Expr\ArrayItem(
                    new Node\Scalar\String_($type),
                    new Node\Scalar\String_($name)
                );
            }
        }

        $node->default = new Node\Expr\Array_($items, [
            'kind' => Node\Expr\Array_::KIND_SHORT,
        ]);
        return $node;
    }
}
