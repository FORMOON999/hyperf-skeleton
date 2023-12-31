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
use Hyperf\DTO\Annotation\Validation\Required;
use Lengbin\Common\BaseObject;

/**
 * Class PlatformCreateRequest.
 */
class PlatformCreateRequest extends BaseObject
{
    #[ApiModelProperty(value: '账号', required: true), Required]
    public string $username;

    #[ApiModelProperty(value: '昵称', required: true), Required]
    public string $nickname;

    #[ApiModelProperty(value: '密码', required: true), Required]
    public string $password;

    #[ApiModelProperty(value: '状态', required: true), Required]
    public BaseStatus $status;
}
