<?php
/**
 * Created by PhpStorm.
 * Date:  2022/3/17
 * Time:  10:10 PM.
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Common\Core\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class ArrayType
{
    public ?string $className;

    public ?string $type;

    public function __construct(?string $className = null, ?string $type = null)
    {
        $this->className = $className;
        $this->type = $type;
    }
}
