<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Entity\Response\Platform\V1\PlatformLoginRecord;

use App\Common\Core\Entity\BaseListResponse;
use App\Model\PlatformLoginRecordEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;

class PlatformLoginRecordListResponse extends BaseListResponse
{
    #[ApiModelProperty('列表')]
    #[ArrayType(className: PlatformLoginRecordEntity::class)]
    public array $list;
}
