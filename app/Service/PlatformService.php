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

namespace App\Service;

use App\Common\Constants\BaseStatus;
use App\Common\Core\Entity\Output;
use App\Common\Exceptions\BusinessException;
use App\Common\Helpers\PasswordHelper;
use App\Constants\Errors\PlatformError;
use App\Infrastructure\PlatformInterface;
use App\Model\Platform;
use App\Model\PlatformEntity;

class PlatformService implements PlatformInterface
{
    public function __construct(protected Platform $platform) {}

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
        $data = $this->check($data);
        $model->fill($data);
        $ret = $model->save();
        return $ret ? $model->getKey() : 0;
    }

    public function modify(array $search, array $data): int
    {
        $data = $this->check($data, $search);
        return $this->platform->buildQuery($search)->update($data);
    }

    public function remove(array $search): ?bool
    {
        return $this->platform->buildQuery($search)->first()?->delete();
    }

    public function detail(array $search, array $field = ['*'], array $withs = [], array $sort = []): ?PlatformEntity
    {
        $query = $this->platform->buildQuery($search, $sort)->select($field);
        if (! empty($withs)) {
            $query->with(...$withs);
        }
        return $query->first()?->newEntity();
    }

    public function login(string $username, string $password): PlatformEntity
    {
        $platform = $this->detail(['username' => $username], [
            'id',
            'password',
            'status',
        ]);
        if (empty($platform)) {
            throw new BusinessException(PlatformError::ACCOUNT_OR_PASSWORD_NOT_FOUND());
        }

        if (! PasswordHelper::verifyPassword($password, $platform->password)) {
            throw new BusinessException(PlatformError::ACCOUNT_OR_PASSWORD_NOT_FOUND());
        }

        // 状态
        if ($platform->status !== BaseStatus::NORMAL()) {
            throw new BusinessException(PlatformError::FROZEN());
        }

        $this->modify(['id' => $platform->id], ['last_time' => date('Y-m-d H:i:s')]);
        return $platform;
    }

    protected function check(array $data, array $search = []): array
    {
        return $data;
    }
}
