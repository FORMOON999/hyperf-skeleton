<?php
declare(strict_types=1);

namespace App\Common\Core\Entity;

use App\Common\Core\BaseObject;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class CommonResponse extends BaseObject
{
    #[ApiModelProperty('状态码')]
    public int $code = 0;

    #[ApiModelProperty('信息')]
    public string $msg = '';

    #[ApiModelProperty('响应数据')]
    public mixed $data;
}
