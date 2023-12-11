<?php
declare(strict_types=1);

namespace App\Common\Core\Entity;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;

class CommonResponse extends BaseObject
{
    #[ApiModelProperty('状态码')]
    public int $code = 0;

    #[ApiModelProperty('信息')]
    public string $msg = '';

    #[ApiModelProperty('响应数据')]
    public mixed $data;
}
