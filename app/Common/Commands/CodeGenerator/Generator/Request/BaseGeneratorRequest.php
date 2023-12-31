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

namespace App\Common\Commands\CodeGenerator\Generator\Request;

use App\Common\Commands\CodeGenerator\Generator\ApplicationGenerator;

abstract class BaseGeneratorRequest extends ApplicationGenerator
{
    public function getPath(string $module = ''): string
    {
        $version = ucfirst($this->config->version);
        $path = $this->ddd ? 'Application' : 'Controller';
        return parent::getPath("/{$path}/{$module}/{$version}/{$this->modelInfo->name}/Request");
    }
}
