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

use App\Common\Util\PhpGenerator\Base;
use App\Common\Util\PhpGenerator\GenerateClass;

class BasePrinter
{
    protected function getSpaces(int $level = 1): string
    {
        return str_repeat(' ', $level * 4);
    }

    protected function renderComment(array $comments = [], int $level = 0): string
    {
        $data = [];
        if (! empty($comments)) {
            $data[] = "{$this->getSpaces($level)}/**";
            foreach ($comments as $comment) {
                $data[] = "{$this->getSpaces($level)} * {$comment}";
            }
            $data[] = "{$this->getSpaces($level)} */";
        }
        return implode("\n", $data);
    }

    /**
     * @param Base|GenerateClass $obj
     */
    protected function renderPrefix($obj): string
    {
        $str = '';
        if ($obj->getFinal()) {
            $str .= 'final ';
        }
        if ($obj->getAbstract()) {
            $str .= 'abstract ';
        }
        return $str;
    }

    /**
     * 获取 作用域
     */
    protected function getScope(Base $base): string
    {
        $str = '';
        if ($base->getPublic()) {
            $str = 'public';
        }

        if ($base->getProtected()) {
            $str = 'protected';
        }

        if ($base->getPrivate()) {
            $str = 'private';
        }

        if ($base->getStatic()) {
            $str .= ' static';
        }
        return $str;
    }
}
