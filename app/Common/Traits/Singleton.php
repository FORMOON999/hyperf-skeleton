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

namespace App\Common\Traits;

trait Singleton
{
    private static $instance;

    public static function getInstance(...$args)
    {
        if (! isset(self::$instance)) {
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }
}
