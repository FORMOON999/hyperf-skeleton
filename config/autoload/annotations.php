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
    'scan' => [
        'paths' => [
            BASE_PATH . '/app',
        ],
        'ignore_annotations' => [
            'mixin',
        ],
        'class_map' => [
            // 字典
            Hyperf\DTO\Scan\PropertyEnum::class => BASE_PATH . '/app/Common/Core/CLassMap/PropertyEnum.php',
            Hyperf\DTO\JsonMapper::class => BASE_PATH . '/app/Common/Core/CLassMap/JsonMapper.php',
            Hyperf\DTO\Aspect\CoreMiddlewareAspect::class => BASE_PATH . '/app/Common/Core/CLassMap/CoreMiddlewareAspect.php',
            Hyperf\ApiDocs\Swagger\GenerateResponses::class => BASE_PATH . '/app/Common/Core/CLassMap/GenerateResponses.php',
        ],
    ],
];
