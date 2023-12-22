<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/15
 * Time:  5:41 PM
 */

declare(strict_types=1);

namespace App\Common\Util\Auth\Exception;

use Hyperf\Server\Exception\RuntimeException;

class TokenExpireException extends RuntimeException
{

}
