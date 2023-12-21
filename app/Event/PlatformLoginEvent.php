<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/14
 * Time:  4:55 PM.
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

namespace App\Event;

class PlatformLoginEvent
{
    public int $platformId;

    public function __construct(int $platformId)
    {
        $this->platformId = $platformId;
    }
}
