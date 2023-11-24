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

namespace App\Common\Traits;

use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Throwable;

trait ExceptionFormatTrait
{
    protected ?FormatterInterface $_formatter = null;

    public function formatException(Throwable $throwable): string
    {
        return $this->getFormatter()->format($throwable);
    }

    private function getFormatter(): FormatterInterface
    {
        if (is_null($this->_formatter)) {
            $this->_formatter = \Hyperf\Support\make(FormatterInterface::class);
        }
        return $this->_formatter;
    }
}
