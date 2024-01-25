<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Platform\Response;

use App\Common\Constants\BaseStatus;
use App\Common\Core\Entity\BaseModelEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;

class PlatformDetailResponse extends BaseModelEntity
{
    #[ApiModelProperty('账号')]
    public string $username;

    #[ApiModelProperty('昵称')]
    public string $nickname;

    #[ApiModelProperty('状态')]
    public BaseStatus $status;
    #[ApiModelProperty('上次登录时间')]
    public string $lastTime;

    #[ApiModelProperty(value: '角色'), ArrayType(type: 'string')]
    public array $roles;

    #[ApiModelProperty(value: '权限'), ArrayType(type: 'string')]
    public array $perms;
}