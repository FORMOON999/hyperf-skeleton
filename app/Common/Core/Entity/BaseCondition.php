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

namespace App\Common\Core\Entity;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\BaseObject;

class BaseCondition extends BaseObject
{
    #[ApiModelProperty(value: '是否格式化', hidden: true)]
    public int $_format = 1;

    #[ApiModelProperty(value: '是否不抛异常', hidden: true)]
    public int $_throw = 1;

    #[ApiModelProperty(value: '是否强制删除', hidden: true)]
    public int $_delete = 0;
}
