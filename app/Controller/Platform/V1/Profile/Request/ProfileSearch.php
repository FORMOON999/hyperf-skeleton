<?php

namespace App\Controller\Platform\V1\Profile\Request;

use App\Common\Core\ApiDocs\Annotation\ApiAttributeProperty;
use Lengbin\Common\BaseObject;

class ProfileSearch extends BaseObject
{
    #[ApiAttributeProperty]
    public int $id;
}