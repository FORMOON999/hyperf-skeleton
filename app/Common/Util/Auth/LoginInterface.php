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

namespace App\Common\Util\Auth;

interface LoginInterface
{
    public function makeToke(string $sub, ?string $iss = null, array $data = []): string;

    public function logout(string $token): bool;

    public function refreshToken(string $token): string;

    public function verifyToken(?string $token, bool $ignoreExpired = false): JwtSubject;

    public function getTtl(): int;
}
