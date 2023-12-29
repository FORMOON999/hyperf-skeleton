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

namespace App\Common\Core\ApiDocs\Annotation;

use Attribute;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use OpenApi\Generator;

#[Attribute(Attribute::TARGET_PROPERTY)]
class ApiAttributeProperty extends ApiModelProperty
{
    public function __construct(?string $value = null, mixed $example = Generator::UNDEFINED, bool $hidden = true, ?bool $required = null)
    {
        parent::__construct($value, $example, $hidden, $required);
    }
}
