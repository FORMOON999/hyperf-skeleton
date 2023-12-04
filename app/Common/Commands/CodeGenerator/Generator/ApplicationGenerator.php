<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;

abstract class ApplicationGenerator extends AbstractGenerator
{
    public function run(array $results = [])
    {
        $data = [];
        foreach ($this->config->applications as $application) {
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
        if ($this->isWrite() && !file_exists($class->file)) {
            $this->mkdir($class->file);
            file_put_contents($class->file, $this->buildClass($class, $results));
        }
        return $class;
    }

}