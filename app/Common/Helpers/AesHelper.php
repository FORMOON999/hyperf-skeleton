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
    protected string $key;

    protected string $method;

    public function __construct(string $key = 'hyperf', string $method = 'AES-128-CBC')
    {
        $this->key = $key;
        $this->method = $method;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function encrypt($data): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
        $encrypted = openssl_encrypt($data, $this->method, $this->key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    public function decrypt($data): string
    {
        [$encryptedData, $iv] = explode('::', base64_decode($data), 2);
        return openssl_decrypt($encryptedData, $this->method, $this->key, 0, $iv);
    }
}