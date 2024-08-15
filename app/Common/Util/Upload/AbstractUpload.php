<?php
/**
 * Created by PhpStorm.
 * Date:  2021/11/11
 * Time:  11:24 上午.
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Common\Util\Upload;

use App\Common\Util\Upload\Helper\ImageHelper;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Support\Filesystem\Filesystem;

abstract class AbstractUpload implements UploadInterface
{
    protected UploadConfig $config;

    #[Inject]
    public Filesystem $filesystem;

    #[Inject]
    public ImageHelper $imageHelper;

    public function loadConfig(UploadConfig $config): static
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig(): UploadConfig
    {
        return $this->config;
    }

    public function mkdir(string $tagDir): void
    {
        if (!$this->filesystem->isDirectory($tagDir)) {
            $this->filesystem->makeDirectory($tagDir, 0755, true);
        }
    }
}
