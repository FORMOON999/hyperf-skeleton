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

namespace App\Controller\Platform\V1\Menu\Response;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Lengbin\Common\Annotation\ArrayType;
use Lengbin\Common\BaseObject;

class MenuRoutResponse extends BaseObject
{
    #[ApiModelProperty('列表'), ArrayType(MenuRoutItem::class)]
    public array $list;
}
