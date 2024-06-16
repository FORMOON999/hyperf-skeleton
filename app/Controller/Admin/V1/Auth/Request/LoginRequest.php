<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/13
 * Time:  6:19 PM.
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Admin\V1\Auth\Request;

use App\Common\Core\BaseObject;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Hyperf\DTO\Annotation\Validation\Str;

class LoginRequest extends BaseObject
{
    #[ApiModelProperty('账号'), Str, Required]
    public string $username;

    #[ApiModelProperty('密码'), Str, Required]
    public string $password;

    #[ApiModelProperty('验证码Key'), Str, Required]
    public string $captchaKey;

    #[ApiModelProperty('验证码Code'), Str, Required]
    public string $captchaCode;
}
