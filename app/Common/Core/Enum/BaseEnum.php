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

use MabeEnum\Enum;
use MabeEnum\EnumSerializableTrait;
use ReflectionClassConstant;
use Serializable;
use UnitEnum;

abstract class BaseEnum extends Enum implements Serializable
{
    use EnumSerializableTrait;
    use EnumTrait;
    use EnumMessageTrait;

    /**
     * 获得.
     */
    protected function getDocCommentMessage(array $replace = []): string
    {
        $classname = get_called_class();
        $constant = new ReflectionClassConstant($classname, $this->getName());
        return $this->handleMessage($constant, $replace);
    }

    //    public static function getMessages(array $replace = []): array
    //    {
    //        $classname = get_called_class();
    //        $reflect = new ReflectionClass($classname);
    //        $constants = $reflect->getReflectionConstants();
    //        $data = [];
    //        foreach ($constants as $constant) {
    //            $data[] = self::handleMessage($constant, $replace);
    //        }
    //        return $data;
    //    }

    //    /**
    //     * map.
    //     * @return array
    //     */
    //    public static function getMapJson()
    //    {
    //        $data = [];
    //        $values = static::getValues();
    //        foreach ($values as $value) {
    //            $data[] = [
    //                'value' => $value,
    //                'message' => static::byValue($value)->getMessage(),
    //            ];
    //        }
    //        return $data;
    //    }
}
