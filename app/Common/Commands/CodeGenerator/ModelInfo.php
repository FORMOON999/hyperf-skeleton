<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator;

class ModelInfo extends ClassInfo
{
    // 字段
    public array $columns;

    // 模块
    public string $module;

    // 主键
    public string $pk;

    // 表的备注
    public string $comment;

    // 是否存在
    public bool $exist;
}