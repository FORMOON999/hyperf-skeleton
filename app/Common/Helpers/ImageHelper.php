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

namespace App\Common\Helpers;

class ImageHelper
{
    public function makePath(string $key, string $host): string
    {
        if (empty($key)) {
            return $key;
        }
        if (RegularHelper::checkUrl($key)) {
            return $key;
        }
        return rtrim($host, "\t\n\r\0\x0B/") . '/' . ltrim($key, '/');
    }
}
