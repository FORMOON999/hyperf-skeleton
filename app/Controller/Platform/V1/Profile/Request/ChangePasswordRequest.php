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

namespace App\Controller\Platform\V1\Profile\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Hyperf\DTO\Annotation\Validation\Validation;
use Lengbin\Common\BaseObject;

class ChangePasswordRequest extends BaseObject
{
    #[ApiModelProperty('密码'), Required]
    public string $password;
    #[ApiModelProperty('确认密码'), Required, Validation('confirmed:password')]
    public string $confirmPassword;
}
