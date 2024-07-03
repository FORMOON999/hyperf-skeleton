<?php
declare(strict_types=1);

namespace App\Common\Core\Entity;

use App\Common\Core\BaseObject;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class BaseListResponse extends BaseObject
{
    #[ApiModelProperty('总数')]
    public int $total = 0;

    #[ApiModelProperty('分页')]
    public int $page = 1;

    #[ApiModelProperty('每页数量')]
    public int $pageSize = 20;
}
