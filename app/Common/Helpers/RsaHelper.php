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

namespace App\Common\Helpers;

class RsaHelper
{
    protected string $publicKey;

    protected string $privateKey;

    public function setPublicKey(string $file): static
    {
        if (is_file($file)) {
            $this->publicKey = file_get_contents($file);
        } else {
            $this->publicKey = $file;
        }
        return $this;
    }

    public function setPrivateKey(string $file): static
    {
        if (is_file($file)) {
            $this->privateKey = file_get_contents($file);
        } else {
            $this->privateKey = $file;
        }
        return $this;
    }

    public function encrypt($data): string
    {
        openssl_public_encrypt($data, $encrypted, $this->publicKey);
        return base64_encode($encrypted);
    }

    public function decrypt($data): string
    {
        $decrypted = base64_decode($data);
        openssl_private_decrypt($decrypted, $decrypted, $this->privateKey);
        return $decrypted;
    }
}
