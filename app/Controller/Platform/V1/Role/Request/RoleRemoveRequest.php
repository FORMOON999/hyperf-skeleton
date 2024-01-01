<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Role\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class RoleRemoveRequest
 * @package App\Controller\Platform\V1\Role\Request
 */
class RoleRemoveRequest extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty(value: '角色管理ID', required: true), Required]
    public int $id;

}