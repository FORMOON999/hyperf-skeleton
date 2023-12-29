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

namespace App\Infrastructure;

use App\Common\Core\Entity\Output;
use App\Model\PlatformEntity;

interface PlatformInterface
{
    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output;

    public function create(array $data): int|string;

    public function modify(array $search, array $data): int;

    public function remove(array $search): int;

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?PlatformEntity;

    public function changePassword(int $id, string $password): int;
}
