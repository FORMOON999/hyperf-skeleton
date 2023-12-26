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

namespace App\Entity\Request\Platform\V1\Platform;

use App\Model\PlatformEntity;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

class PlatformModifyRequest extends BaseObject
{
    #[ApiModelProperty('搜索参数'), Required]
    public PlatformSearch $search;

    #[ApiModelProperty('请求数据'), Required]
    public PlatformEntity $data;
}
