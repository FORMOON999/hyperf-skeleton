<?php

declare(strict_types=1);

namespace App\Controller\Admin\V1\Menu\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class MenuRemoveRequest
 * @package App\Controller\Admin\V1\Menu\Request
 */
class MenuRemoveRequest extends \App\Common\Core\BaseObject
{

    #[ApiModelProperty(value: '菜单管理ID', required: true), Required]
    public int $id;

}