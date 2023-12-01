<?php

namespace App\Common\Core\Entity;

use Lengbin\Common\BaseObject;

class OutputEntity extends BaseObject
{
    public ?int $page;

    public ?int $pageSize;

    public ?int $total;

    public array $list = [];
}