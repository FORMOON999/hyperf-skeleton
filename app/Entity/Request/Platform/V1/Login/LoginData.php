<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/13
 * Time:  6:25 PM.
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
namespace App\Entity\Request\Platform\V1\Login;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;
use Hyperf\DTO\Annotation\Validation\Str;
use Lengbin\Common\BaseObject;

class LoginData extends BaseObject
{
    #[ApiModelProperty('地址'), Str, Required]
    public string $address;

    #[ApiModelProperty('签名'), Str, Required]
    public string $sign;
}
