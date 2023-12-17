<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class PlatformEntity
 * @package App\Model
 */
class PlatformEntity extends \App\Common\Core\Entity\BaseModelEntity
{

    #[ApiModelProperty("账号")]
    public string $username;

    #[ApiModelProperty("昵称")]
    public string $nickname;

    #[ApiModelProperty("密码")]
    public string $password;

    #[ApiModelProperty("状态")]
    public int $status;

}