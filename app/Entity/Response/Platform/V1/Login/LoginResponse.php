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

namespace App\Entity\Response\Platform\V1\Login;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;

class LoginResponse extends BaseObject
{
    #[ApiModelProperty('Token')]
    public string $token;

    #[ApiModelProperty('过期时间')]
    public int $expire = 0;
}
