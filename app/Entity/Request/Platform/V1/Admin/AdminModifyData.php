<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\Admin;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class AdminModifyData
 * @package App\Entity\Request\Platform\V1\Admin
 */
class AdminModifyData extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("账号")]
    public string $username;

    #[ApiModelProperty("密码")]
    public string $password;

    #[ApiModelProperty("昵称")]
    public int $status;

}