<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator;

class GeneratorConfig
{
    // 路径
    public string $path;

    // 版本
    public string $version;

    // 路由
    public string $url;

    // 引用
    public array $applications;
}