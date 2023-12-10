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

use Throwable;

trait EnumTrait
{
    public static function from($value): static
    {
        return static::byValue($value);
    }

    public static function tryFrom($value): ?static
    {
        try {
            return static::byValue($value);
        } catch (Throwable $exception) {
            return null;
        }
    }

    public static function cases(): array
    {
        return static::getEnumerators();
    }

    public function __get(string $name)
    {
        if ($name == "value") {
            return $this->getValue();
        }
        if ($name == "name") {
            return $this->getName();
        }
        if ($name == "message") {
            return $this->getMessage();
        }

        return self::__get($name);
    }

}
