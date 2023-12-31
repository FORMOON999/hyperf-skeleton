<?php

declare(strict_types=1);

namespace App\Service;

use App\Infrastructure\PlatformInterface;
use App\Model\Platform;
use App\Model\PlatformEntity;
use App\Common\Core\Entity\Output;
use Lengbin\Helper\Util\PasswordHelper;

class PlatformService implements PlatformInterface
{

    public function __construct(protected Platform $platform)
    {

    }

    public function getList(array $search, array $field = ['*'], array $withs = [], array $sort = [], array $page = []): Output
    {
        $query = $this->platform->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $this->platform->output($query, $page);
    }

    public function create(array $data): int|string
    {
        $model = clone $this->platform;
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        return $this->platform->buildQuery($search)->update($data);
    }

    public function remove(array $search): int
    {
        return $this->platform->buildQuery($search)->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?PlatformEntity
    {
        $query = $this->platform->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }
}
