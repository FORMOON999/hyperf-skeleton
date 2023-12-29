<?php

declare(strict_types=1);

namespace App\Common\Core\Entity;

use App\Common\Constants\SortType;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;
class BaseSort extends BaseObject
{
    #[ApiModelProperty('创建时间排序')]
    public SortType $createdAt;

    public function toArray(): array
    {
        $result = parent::toArray();
        if (! empty($result)) {
            return $result;
        }
        $this->createdAt = SortType::DESC();
        return parent::toArray();
    }
}
