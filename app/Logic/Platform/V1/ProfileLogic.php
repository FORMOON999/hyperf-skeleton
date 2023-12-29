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
use App\Controller\Platform\V1\Profile\Request\ProfileDetailRequest;
use App\Controller\Platform\V1\Profile\Response\ProfileResponse;
use App\Infrastructure\PlatformInterface;
use Hyperf\Di\Annotation\Inject;

class ProfileLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    public function detail(ProfileDetailRequest $request): ProfileResponse
    {
        $result = $this->platform->detail(
            $request->search->toArray(),
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
            ],
        );
        if (! $result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        return new ProfileResponse($result);
    }

    public function changePassword(ChangePasswordRequest $request): BaseSuccessResponse
    {
        $result = $this->platform->changePassword($request->search->id, $request->data->password);
        if (! $result) {
            throw new BusinessException(PlatformError::UPDATE_ERROR());
        }
        return new BaseSuccessResponse();
    }
}
