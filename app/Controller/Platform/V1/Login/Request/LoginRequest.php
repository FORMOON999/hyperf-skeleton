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
namespace App\Controller\Platform\V1\Login\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Hyperf\DTO\Annotation\Validation\Str;
use Lengbin\Common\BaseObject;

class LoginRequest extends BaseObject
{
    #[ApiModelProperty('账号'), Str, Required]
    public string $username;

    #[ApiModelProperty('密码'), Str, Required]
    public string $password;
}
