<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\PlatformLoginRecord;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class PlatformLoginRecordRemoveSearch
 * @package App\Entity\Request\Platform\V1\PlatformLoginRecord
 */
class PlatformLoginRecordRemoveSearch extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("管理台登录日志ID"), Required]
    public int $id;

}