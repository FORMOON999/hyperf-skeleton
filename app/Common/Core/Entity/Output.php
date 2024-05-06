<?php

namespace App\Common\Core\Entity;

use App\Common\Core\BaseObject;

class Output extends BaseObject
{
    public ?int $page = null;

    public ?int $pageSize = null;

    public ?int $total = null;

    public array $list = [];
}