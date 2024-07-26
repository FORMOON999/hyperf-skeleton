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

namespace App\Common\Core\Enum;

use BackedEnum;

interface MessageBackedEnum extends BackedEnum
{
    public function getMessage(array $replace = [], ?string $locale = null): string;
}
