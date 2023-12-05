<?php

declare(strict_types=1);

namespace App\Entity\Request\App\V1\Admin;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;
use Hyperf\DTO\Annotation\Validation\Required;

/**
 * Class AdminSearch
 * @package App\Entity\Request\App\V1\Admin
 */
class AdminSearch extends \Lengbin\Common\BaseObject
{

    #[ApiModelProperty("管理员ID"), Required]
    public int $id;

}