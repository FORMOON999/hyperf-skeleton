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

class RegularHelper
{
    /**
     * 正则.
     */
    public static function checkUrl(string $url): bool
    {
        if (StringHelper::isEmpty($url)) {
            return false;
        }
        $rule = '/((http|https):\\/\\/)+(\\w+)[\\w\\/\\.\\-]*/';
        return preg_match($rule, $url);
    }

    /**
     * 正则.
     *
     * @param string $url
     */
    public static function checkImage($url): bool
    {
        if (StringHelper::isEmpty($url)) {
            return false;
        }
        $rule = '/((http|https):\\/\\/)?\\w+\\.(jpg|jpeg|gif|png)/';
        return preg_match($rule, $url);
    }

    /**
     * 密码
     */
    public static function isInvalidPassword($password): bool
    {
        if (StringHelper::isEmpty($password)) {
            return true;
        }
        $rule = '/^(?=.*[a-zA-Z0-9].*)(?=.*[a-zA-Z\W].*)(?=.*[0-9\W].*).{6,20}$/';
        return ! preg_match($rule, $password);
    }
}
