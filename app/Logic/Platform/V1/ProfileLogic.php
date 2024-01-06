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

namespace App\Logic\Platform\V1;

use App\Common\Core\Entity\BaseSuccessResponse;
use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\PlatformError;
use App\Controller\Platform\V1\Profile\Request\ChangePasswordRequest;
use App\Controller\Platform\V1\Profile\Response\ProfileResponse;
use App\Infrastructure\PlatformInterface;
use App\Infrastructure\RoleInterface;
use Hyperf\Di\Annotation\Inject;
use Lengbin\Helper\Util\PasswordHelper;

class ProfileLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    #[Inject]
    protected RoleInterface $role;

    public function detail(int $id): ProfileResponse
    {
        $result = $this->platform->detail(
            ['id' => $id],
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
                'role',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        $response = new ProfileResponse($result);
        $response->perms = $this->role->getPermissionByRoles($result->role);
        return $response;
    }

    public function changePassword(int $id, ChangePasswordRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->modify(['id' => $id], ['password' => PasswordHelper::generatePassword($request->password)]);
        if (! $result) {
            throw new BusinessException(PlatformError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }
}
