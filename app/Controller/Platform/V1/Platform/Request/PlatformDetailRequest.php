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

namespace App\Controller\Platform\V1\Platform\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

/**
 * Class PlatformDetailRequest.
 */
class PlatformDetailRequest extends BaseObject
{
    #[ApiModelProperty(value: '管理员ID', required: true), Required]
    public int $id;
}
