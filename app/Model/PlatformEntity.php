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

namespace App\Model;

use App\Common\Constants\BaseStatus;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class PlatformEntity.
 */
class PlatformEntity extends \App\Common\Core\Entity\BaseModelEntity
{
    #[ApiModelProperty('账号')]
    public string $username;

    #[ApiModelProperty('昵称')]
    public string $nickname;

    #[ApiModelProperty('密码')]
    public string $password;

    #[ApiModelProperty('状态')]
    public BaseStatus $status;
    #[ApiModelProperty(value: '上次登录时间')]
    public string $lastTime;
}
