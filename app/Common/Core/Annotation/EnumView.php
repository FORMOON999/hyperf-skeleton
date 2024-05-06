<?php
/**
 * Created by PhpStorm.
 * Date:  2022/3/17
 * Time:  10:10 PM
 */

declare(strict_types=1);

namespace App\Common\Core\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class EnumView
{
    public const ENUM_NAME = 1;
    public const ENUM_VALUE = 2;
    public const ENUM_MESSAGE = 3;
    public const ENUM_ALL = 4;

    public int $flags;

    public function __construct($flags = null)
    {
        $this->flags = $flags ?: self::ENUM_ALL;
    }
}
