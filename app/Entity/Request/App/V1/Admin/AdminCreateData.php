<?php

declare(strict_types=1);

namespace App\Entity\Request\App\V1\Admin;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class AdminCreateData
 * @package App\Entity\Request\App\V1\Admin
 */
class AdminCreateData extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("账号"), Required]
    public string $username;

    #[ApiModelProperty("密码"), Required]
    public string $password;

    #[ApiModelProperty("昵称"), Required]
    public int $status;

}