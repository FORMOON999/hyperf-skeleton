<?php
declare(strict_types=1);

namespace App\Common\Core\Entity;

use App\Common\Core\BaseObject;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class BaseSuccessResponse extends BaseObject
{
    #[ApiModelProperty('请求结果')]
    public bool $result = true;
}
