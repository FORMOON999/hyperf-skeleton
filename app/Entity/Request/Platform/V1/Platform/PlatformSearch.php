<?php

declare(strict_types=1);

namespace App\Entity\Request\Platform\V1\Platform;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class PlatformSearch
 * @package App\Entity\Request\Platform\V1\Platform
 */
class PlatformSearch extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("管理台ID"), Required]
    public int $id;

}