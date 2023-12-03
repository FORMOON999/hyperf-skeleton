<?php
declare(strict_types=1);

namespace App\Common\Core\Entity;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;

class BaseListResponse extends BaseObject
{
    #[ApiModelProperty('页码')]
    public ?int $page = 1;

    #[ApiModelProperty('每页数')]
    public ?int $pageSize = 20;

    #[ApiModelProperty('总数')]
    public ?int $total = 0;
}
