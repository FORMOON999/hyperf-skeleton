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

use App\Common\Core\BaseObject;

class ClassInfo extends BaseObject
{
    // 名称
    public string $name;

    // 命名空间
    public string $namespace;

    // 文件
    public string $file;
}
