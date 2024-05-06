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

namespace App\Common\Util\PhpGenerator\Printer;

use App\Common\Traits\Singleton;
use Exception;

class PrinterFactory
{
    use Singleton;

    public const VERSION_PHP72 = 72;

    public const VERSION_PHP74 = 74;

    public const VERSION_PHP80 = 80;

    public function getPrinter(int $version): PrinterInterface
    {
        switch ($version) {
            case self::VERSION_PHP72:
                $printer = new PrinterPhp72();
                break;
            case self::VERSION_PHP74:
                $printer = new PrinterPhp74();
                break;
            case self::VERSION_PHP80:
                $printer = new PrinterPhp80();
                break;
            default:
                throw new Exception('Kind must be one of ::VERSION_PHP72, ::VERSION_PHP74 or ::VERSION_PHP80');
        }
        return $printer;
    }
}
