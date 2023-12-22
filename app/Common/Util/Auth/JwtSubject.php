<?php
/**
 * Created by PhpStorm.
 * Date:  2021/10/22
 * Time:  6:51 下午.
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

namespace App\Common\Util\Auth;

use HyperfExt\Jwt\Contracts\JwtSubjectInterface;

class JwtSubject implements JwtSubjectInterface
{
    public array $data = [];

    /**
     * 是否过期
     */
    public bool $expired = false;

    /**
     * 是否失效.
     */
    public bool $invalid = false;

    public function getJwtIdentifier()
    {
        return $this->data['sub'] ?? '';
    }

    public function getJwtCustomClaims(): array
    {
        return $this->data;
    }
}
