<?php
declare(strict_types=1);

namespace App\Common\Core\Enum\Annotation;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class EnumMessage
{
    /**
     * 枚举描述
     * @var string
     */
    public string $message;

    public function __construct(string $message = '')
    {
        $this->message = $message;
    }
}
