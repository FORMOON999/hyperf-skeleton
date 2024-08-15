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
        $path = $this->getConfig()->bucket . DIRECTORY_SEPARATOR . $path;
        if (!str_starts_with($path, DIRECTORY_SEPARATOR)) {
            $path = DIRECTORY_SEPARATOR . $path;
        }
        $tagPath = BASE_PATH . DIRECTORY_SEPARATOR . $path;
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
        return [
            'path' => $path,
            'result' => true,
            'message' => '上传成功',
        ];
    }

    public function remove(string $path): bool
    {
        // TODO: Implement remove() method.
    }

    public function has(string $path): bool
    {
        // TODO: Implement has() method.
    }
}
