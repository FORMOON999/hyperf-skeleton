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

use Hyperf\Di\ReflectionManager;

$daoInterface = \Hyperf\Support\value(function () {
    $path = BASE_PATH . '/app/Service';
    if (!is_dir($path)) {
        return [];
    }
    $result = ReflectionManager::getAllClasses([$path]);
    $output = [];
    foreach ($result as $class) {
        /** @var ReflectionClass $class */
        $interfaces = $class->getInterfaceNames();
        if (empty($interfaces)) {
            continue;
        }
        $output[$interfaces[0]] = $class->getName();
    }
    return $output;
});

return array_merge($daoInterface, [
    Hyperf\Database\Commands\ModelCommand::class => App\Common\Commands\Model\ModelCommand::class,
    Hyperf\Contract\StdoutLoggerInterface::class => App\Common\Log\StdoutLoggerFactory::class,
]);
