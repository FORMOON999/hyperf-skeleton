<?php

declare(strict_types=1);

namespace App\Common\Commands\CodeGenerator\Generator;

use App\Common\Commands\Model\ClassInfo;

class DaoGenerator extends AbstractGenerator
{

    public function getPath(string $module = ''): string
    {
        return parent::getPath('/Repository/Dao/MySQL');
    }

    public function getFilename(): string
    {
        return $this->modelInfo->name . 'Dao';
    }

    public function buildClass(ClassInfo $class, array $results = []): string
    {
        $stub = file_get_contents(dirname(__DIR__) . '/stubs/Dao.stub');
        $daoInterface = $results['daoInterface'];
        $this->replaceNamespace($stub, $class->namespace)
            ->replaceClass($stub, $class->name)
            ->replaceInheritance($stub, $daoInterface->name)
            ->replaceUses($stub, [
                $daoInterface->namespace,
                $this->modelInfo->namespace
            ])
            ->replace($stub, '%MODEL%', $this->modelInfo->name);
        return $stub;
    }
}