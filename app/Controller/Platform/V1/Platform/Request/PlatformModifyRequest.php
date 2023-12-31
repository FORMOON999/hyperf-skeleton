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

use App\Common\Constants\BaseStatus;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class PlatformModifyRequest.
 */
class PlatformModifyRequest extends \Lengbin\Common\BaseObject
{
    #[ApiModelProperty(value: '账号')]
    public string $username;

    #[ApiModelProperty(value: '昵称')]
    public string $nickname;

    #[ApiModelProperty(value: '状态')]
    public BaseStatus $status;
}
