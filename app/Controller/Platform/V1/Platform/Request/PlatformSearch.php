<?php

declare(strict_types=1);

namespace App\Controller\Platform\V1\Platform\Request;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class PlatformSearch
 * @package App\Controller\Platform\V1\Platform\Request
 */
class PlatformSearch extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("管理员ID"), Required]
    public int $id;

}