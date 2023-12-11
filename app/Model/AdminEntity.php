<?php

declare(strict_types=1);

namespace App\Model;

use App\Common\Constants\BaseStatus;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class AdminEntity
 * @package App\Model
 */
class AdminEntity extends \App\Common\Core\Entity\BaseModelEntity
{
    #[ApiModelProperty("账号")]
    public string $username;

    #[ApiModelProperty("密码")]
    public string $password;

    #[ApiModelProperty("状态")]
    public BaseStatus $status;
}