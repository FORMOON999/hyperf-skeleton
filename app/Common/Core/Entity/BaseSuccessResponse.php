<?php
declare(strict_types=1);

namespace App\Common\Core\Entity;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;

class BaseSuccessResponse extends BaseObject
{
    #[ApiModelProperty('请求结果')]
    public bool $result = true;
}
