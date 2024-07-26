<?php
/**
 * Created by PhpStorm.
 * Date:  2021/10/22
 * Time:  4:03 下午
 */

declare(strict_types=1);

namespace App\Common\Util\Auth;

use App\Common\Util\Auth\Mode\JwtMode;
use App\Common\Util\Auth\Mode\TokenMode;
use function Hyperf\Support\make;

class LoginFactory
{
    public const LOGIN_MODE_API = 'api';
    public const LOGIN_MODE_SESSION = 'session';

    public const MAP = [
        self::LOGIN_MODE_API => JwtMode::class,
        self::LOGIN_MODE_SESSION => TokenMode::class,
    ];

    public function get(string $mode = self::LOGIN_MODE_API): LoginInterface
    {
        return make(self::MAP[$mode]);
    }
}
