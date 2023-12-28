<?php

namespace App\Logic\Platform\V1;

use App\Common\Exceptions\BusinessException;
use App\Constants\Errors\PlatformError;
use App\Controller\Platform\V1\Profile\Response\ProfileResponse;
use App\Infrastructure\PlatformInterface;
use Hyperf\Di\Annotation\Inject;

class ProfileLogic
{
    #[Inject()]
    protected PlatformInterface $platform;

    public function detail(int $id): ProfileResponse
    {
        $result = $this->platform->detail(
            [
                'id' => $id,
            ],
            [
                'id',
                'created_at',
                'updated_at',
                'username',
                'nickname',
                'status',
            ],
        );
        if (!$result) {
            throw new BusinessException(PlatformError::NOT_FOUND());
        }
        return new ProfileResponse($result);
    }
}