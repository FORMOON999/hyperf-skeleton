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

class AesHelper
{
    protected string $key = 'JQkKNw8GTCQ9h34h';

    protected string $method = 'AES-128-ECB';

    protected string $vi = '';

    public function __construct() {}

    public function setKey(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setVi(string $vi): static
    {
        $this->vi = $vi;
        return $this;
    }

    public function getVi(): string
    {
        return $this->vi;
    }

    public function encrypt(string $data): string
    {
        $encrypted = openssl_encrypt($data, $this->method, $this->key, OPENSSL_RAW_DATA, $this->vi);
        return base64_encode($encrypted);
    }

    public function decrypt(string $data): string
    {
        $encryptedData = base64_decode($data);
        return openssl_decrypt($encryptedData, $this->method, $this->key, OPENSSL_RAW_DATA, $this->vi);
    }
}
