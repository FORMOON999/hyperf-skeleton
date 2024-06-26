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
    public const KEY = '864134';

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

    public function encrypt(string $file, bool $saveOld = false): bool
    {
        if (! is_file($file)) {
            return false;
        }
        $count = strlen(self::KEY);
        $content = file_get_contents($file);
        if ($saveOld) {
            $oldFile = $this->getOldFile($file);
            @file_put_contents($oldFile, $content);
        }
        for ($i = 0; $i < 100; ++$i) {
            $byteValue = ord($content[$i]);
            $byteValue ^= self::KEY[$i % $count];
            $content[$i] = chr($byteValue);
        }
        @file_put_contents($file, $content);
        return true;
    }

    public function getOldFile(string $file): string
    {
        $info = explode('.', $file);
        $info[0] .= '-old';
        return implode('.', $info);
    }
}
