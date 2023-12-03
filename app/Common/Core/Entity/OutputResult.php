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

namespace App\Common\Core\Entity;

use Lengbin\Common\BaseObject;

class OutputResult extends BaseObject
{
    public ?int $page;

    public ?int $pageSize;

    public ?int $total;

    public array $list = [];
}
