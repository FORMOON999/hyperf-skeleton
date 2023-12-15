<?php

namespace App\Common\Core\Entity;

use Lengbin\Common\BaseObject;

class Output extends BaseObject
{
    public ?int $page = null;

    public ?int $pageSize = null;

    public ?int $total = null;

    public array $list = [];
}