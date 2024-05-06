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

use App\Common\Util\PhpGenerator\Property;

class PrinterPhp74 extends PrinterPhp72
{
    public function printProperty(Property $property): string
    {
        $data = [];
        // if not comment, add default value type
        if (empty($property->getComments()) && ! is_null($property->getDefault())) {
            $property->addComment('@var ' . $property->__valueType($property->getDefault()));
        }
        $comment = $this->renderComment($property->getComments(), 1);
        $type = $property->getType();
        $data[] = $comment;
        $str = "{$this->getSpaces()}{$this->getScope($property)} {$type} $" . $property->getName();
        if (is_null($property->getDefault())) {
            $str .= ';';
        } else {
            $str .= (' = ' . $property->__getValue($property->getDefault()) . ';');
        }
        $data[] = $str . "\n";
        return implode("\n", array_filter($data));
    }
}
