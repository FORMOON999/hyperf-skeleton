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

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;

abstract class ApplicationGenerator extends AbstractGenerator
{
    public function run(array $results = [])
    {
        $data = [];
        foreach ($this->config->applications as $application => $mode) {
            $context = [];
            foreach ($results as $key => $result) {
                if ($result instanceof ClassInfo) {
                    $context[$key] = $result;
                } else {
                    if (str_starts_with($key, 'entity_')) {
                        foreach ($result as $k => $v) {
                            if ($v instanceof ClassInfo) {
                                $context[$k] = $v;
                            }
                            if (is_array($v)) {
                                $context[$k] = $v[$application] ?? [];
                            }
                        }
                    } else {
                        $context[$key] = $result[$application] ?? [];
                    }
                }
            }
            $context['_application'] = $application;
            $context['_mode'] = $mode;
            $data[$application] = $this->handle($context, ucfirst($application));
        }
        return $data;
    }

    public function isWrite(): bool
    {
        return true;
    }

    public function handle(array $results, string $application): ClassInfo
    {
        $class = $this->getClassInfo($application);
        if ($this->isWrite() && ! file_exists($class->file)) {
            $this->mkdir($class->file);
            $content = $this->buildClass($class, $results);
            if (! empty($content)) {
                file_put_contents($class->file, $content);
            }
        }
        return $class;
    }
}
