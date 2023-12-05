<?php

declare(strict_types=1);

namespace App\Entity\Response\App\V1\Admin;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class AdminItem
 * @package App\Entity\Response\App\V1\Admin
 */
class AdminItem extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("")]
    public int $id;

    #[ApiModelProperty("账号")]
    public string $username;

    #[ApiModelProperty("密码")]
    public string $password;

    #[ApiModelProperty("昵称")]
    public int $status;

    #[ApiModelProperty("创建时间")]
    public string $createdAt;

    #[ApiModelProperty("更新时间")]
    public string $updatedAt;

    #[ApiModelProperty("删除时间")]
    public string $deletedAt;

}