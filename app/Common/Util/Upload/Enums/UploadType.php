<?php
/**
 * Created by PhpStorm.
 * Date:  2021/11/11
 * Time:  1:18 下午.
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

namespace App\Common\Util\Upload\Enums;

use App\Common\Core\Enum\EnumMessageTrait;

enum UploadType: string
{
    use EnumMessageTrait;

    /**
     * @Message("本地")
     */
    case Local = 'local';

    /**
     * @Message("阿里云Oss")
     */
    case Ali = 'aliyun';

    /**
     * @Message("腾讯云Oss")
     */
    case Tencent = 'tencent';

    /**
     * @Message("七牛云OSss")
     */
    case Qiniu = 'qiniu';
}
