<?php

namespace App\Model;

use App\Common\Core\Entity\BaseModelEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class AdminEntity extends BaseModelEntity
{
    #[ApiModelProperty('aaa')]
    public string $username;

    #[ApiModelProperty('bbb')]
    public string $password;
}