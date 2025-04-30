<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/8
 * Time:  6:31 PM.
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

namespace App\Common\Util\Upload\Type;

use App\Common\Util\Upload\AbstractUpload;

class UploadLocal extends AbstractUpload
{
    public function getToken(string $key)
    {
        // TODO: Implement getToken() method.
    }

    public function uploadFile(string $path, string $file, bool $isEncrypt = false, bool $saveOld = false)
    {
        $path = DIRECTORY_SEPARATOR . $path;
        $tagPath = BASE_PATH . DIRECTORY_SEPARATOR . $this->getConfig()->bucket . $path;
        $tagDir = $this->filesystem->dirname($tagPath);
        $this->mkdir($tagDir);

        $moved = php_sapi_name() == 'cli' ? rename($file, $tagPath) : move_uploaded_file($file, $tagPath);
        if (!$moved) {
            return [
                'path' => $path,
                'result' => false,
                'message' => '上传失败',
            ];
        }
        if ($isEncrypt) {
            sleep(1);
            $this->imageHelper->encrypt($tagPath, $saveOld);
        }
        $this->filesystem->chmod($tagPath, 0644);
        return [
            'path' => $path,
            'result' => true,
            'message' => '上传成功',
        ];
    }

    public function remove(string $path): bool
    {
        if ($this->has($path)) {
            return $this->filesystem->delete($path);
        }
        return false;
    }

    public function has(string $path): bool
    {
        return $this->filesystem->isFile($path);
    }
}
