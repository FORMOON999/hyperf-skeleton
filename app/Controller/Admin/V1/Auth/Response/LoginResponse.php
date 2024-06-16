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

namespace App\Controller\Admin\V1\Auth\Response;

use App\Common\Core\BaseObject;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class LoginResponse extends BaseObject
{
    #[ApiModelProperty('AccessToken')]
    public string $accessToken;

    #[ApiModelProperty('过期时间')]
    public int $expires = 0;

    #[ApiModelProperty('RefreshToken')]
    public string $refreshToken;

    #[ApiModelProperty('TokenType')]
    public string $tokenType = 'Bearer';
}
