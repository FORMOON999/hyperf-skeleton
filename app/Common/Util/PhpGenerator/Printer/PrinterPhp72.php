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

namespace App\Common\Util\PhpGenerator\Printer;

use App\Common\Util\PhpGenerator\Constant;
use App\Common\Util\PhpGenerator\GenerateClass;
use App\Common\Util\PhpGenerator\Method;
use App\Common\Util\PhpGenerator\Params;
use App\Common\Util\PhpGenerator\Property;

class PrinterPhp72 extends BasePrinter implements PrinterInterface
{
    public function printClass(GenerateClass $generateClass): string
    {
        // namespace
        $namespace = ! empty($generateClass->getNamespace()) ? "namespace {$generateClass->getNamespace()};" : '';
        // uses
        $uses = [];
        if (! empty($generateClass->getUses())) {
            foreach ($generateClass->getUses() as $use) {
                $uses[] = "use {$use};";
            }
        }

        // class comments
        if (empty($generateClass->getComments())) {
            $comments = [
                'Class ' . $generateClass->getClassname(),
            ];
            if (! empty($generateClass->getNamespace())) {
                $comments[] = '@package ' . $generateClass->getNamespace();
            }
            $generateClass->setComments($comments);
        }

        // class body
        $body = array_map(function ($result) use ($generateClass) {
            $result->setVersion($generateClass->getVersion());
            return $result->__toString();
        }, array_merge($generateClass->getConstants(), $generateClass->getProperties(), $generateClass->getMethods()));

        // traits
        $traits = [];
        if (! empty($generateClass->getTraits())) {
            foreach ($generateClass->getTraits() as $trait) {
                $traits[] = $this->getSpaces() . "use {$trait};";
            }
            $traits[] = '';
        }

        // class
        $class = array_merge([
            $this->renderComment($generateClass->getComments()),
            $this->renderClassname($generateClass),
            '{',
            implode("\n", $traits),
        ], $body, ['}']);

        return implode("\n\n", array_filter([
            // <?php
            '<?php',
            // StrictTypes
            $generateClass->getStrictTypes() ? 'declare(strict_types=1);' : '',
            // namespace
            $namespace,
            // uses
            implode("\n", $uses),
            // class
            implode("\n", $class),
        ]));
    }

    public function printConstant(Constant $constant): string
    {
        $data = [];
        // if not comment, add name
        if (empty($constant->getComments())) {
            $constant->addComment($constant->getName());
        }
        if (! empty($constant->getComments())) {
            $data[] = $this->renderComment($constant->getComments(), 1);
        }
        $data[] = ("{$this->getSpaces()}{$this->getScope($constant)} const " . strtoupper($constant->getName()) . ' = ' . $constant->__getValue($constant->getDefault()) . ";\n");
        return implode("\n", array_filter($data));
    }

    public function printProperty(Property $property): string
    {
        $data = [];
        // if not comment, add default value type
        if (empty($property->getComments()) && ! is_null($property->getDefault())) {
            $property->addComment('@var ' . $property->__valueType($property->getDefault()));
        }
        $data[] = $this->renderComment($property->getComments(), 1);
        $str = "{$this->getSpaces()}{$this->getScope($property)} $" . $property->getName();
        if (is_null($property->getDefault())) {
            $str .= ';';
        } else {
            $str .= (' = ' . $property->__getValue($property->getDefault()) . ';');
        }
        $data[] = $str . "\n";
        return implode("\n", array_filter($data));
    }

    public function printMethod(Method $method): string
    {
        $data = [];
        $str = $this->getSpaces() . $this->renderPrefix($method) . $this->getScope($method) . " function {$method->getName()}({$this->renderParams($method)})";
        if (! empty($method->getReturn())) {
            $str .= ": {$method->getReturn()}" . ($method->getAbstract() ? ';' : '');
        } else {
            $method->setReturn('mixed');
        }
        $method->addComment("@return {$method->getReturn()}");

        $data[] = $this->renderComment($method->getComments(), 1);
        $data[] = $str;
        if (! $method->getAbstract()) {
            $data[] = "{$this->getSpaces()}{";
            if (empty($method->getContent())) {
                $method->setContent("{$this->getSpaces(2)}// TODO: Implement {$method->getName()}() method.");
            }
            $data[] = $method->getContent();
            $data[] = "{$this->getSpaces()}}\n";
        }
        return implode("\n", array_filter($data));
    }

    protected function renderClassname(GenerateClass $generateClass): string
    {
        $str = $this->renderPrefix($generateClass) . ($generateClass->getInterface() ? 'interface' : 'class') . " {$generateClass->getClassname()}";

        if (! empty($generateClass->getInheritance())) {
            $str .= " extends {$generateClass->getInheritance()}";
        }
        if (! empty($generateClass->getImplements())) {
            $implement = implode(', ', $generateClass->getImplements());
            $str .= " implements {$implement}";
        }
        return $str;
    }

    protected function renderParams(Method &$method): string
    {
        if (empty($method->getParams())) {
            return '';
        }
        $data = [];

        /**
         * @var Params $param
         */
        foreach ($method->getParams() as $param) {
            $classParamName = '$' . $param->getName();
            if (empty($param->getType()) && ! empty($param->getDefault())) {
                $param->setType($method->__valueType($param->getDefault()));
            }
            $classParam = '';
            if (! empty($param->getType())) {
                $classParam .= "{$param->getType()} ";
            } else {
                $param->setType('mixed');
            }
            $classParam .= $classParamName;
            if ($param->getAssign()) {
                $classParam .= (' = ' . $method->__getValue($param->getDefault()));
            }
            $data[] = $classParam;
            $type = str_replace('?', 'null|', $param->getType());
            $method->addComment("@param {$type} {$classParamName} {$param->getComment()}");
        }
        return implode(', ', $data);
    }
}
