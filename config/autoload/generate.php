<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    // 应用端
    'applications' => [
        'api' => 'post',
        'admin' => 'reset',
    ],
    // 是否基于 表区分  ddd
    'for_table_ddd' => false,
    // 模块
    'modules' => [],
    // 模式  reset or post
    'mode' => 'reset',
];
