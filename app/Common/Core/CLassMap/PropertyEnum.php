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

namespace Hyperf\DTO\Scan;

use App\Common\Core\Enum\BaseEnum;
use BackedEnum;
use ReflectionEnum;
use ReflectionException;

class PropertyEnum
{
    /**
     * 返回的类型.
     */
    public ?string $backedType = null;

    /**
     * 名称.
     */
    public ?string $className = null;

    /**
     * 枚举类 value列表.
     */
    public ?array $valueList = null;

    public static function get(string $className): ?PropertyEnum
    {
        /* @phpstan-ignore-next-line */
        if ((PHP_VERSION_ID < 80100 || ! is_subclass_of($className, BackedEnum::class)) && ! is_subclass_of($className, BaseEnum::class)) {
            return null;
        }
        $propertyEnum = new PropertyEnum();
        $propertyEnum->backedType = 'string';
        if (is_subclass_of($className, BackedEnum::class)) {
            try {
                /* @phpstan-ignore-next-line */
                $rEnum = new ReflectionEnum($className);
                $propertyEnum->backedType = (string) $rEnum->getBackingType();
            } catch (ReflectionException) {
            }
        }

        if (is_subclass_of($className, BaseEnum::class)) {
            $values = $className::cases();
            if (! empty($values)) {
                $value = $className::cases()[0]->getValue();
                $propertyEnum->backedType = gettype($value);
            }
        }

        $propertyEnum->className = trim($className, '\\');
        $propertyEnum->valueList = \Hyperf\Collection\collect($className::cases())->map(fn ($v) => "{$v->value}")->all();
        return $propertyEnum;
    }
}
