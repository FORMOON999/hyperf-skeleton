<?php

namespace App\Common\Helpers;

class AesHelper
{
    protected string $key;

    protected string $iv;

    protected string $method = 'AES-128-CBC';

    public function setKey(string $key): static
    {
        $this->key = $key;
        return $this;
    }

    public function setIv(string $iv): static
    {
        $this->iv = $iv;
        return $this;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;
        return $this;
    }

    public function encrypt(string $data): string
    {
        return openssl_encrypt($data, $this->method, $this->key, 0, $this->iv);
    }

    public function decrypt(string $data): string
    {
        return openssl_decrypt($data, $this->method, $this->key, 0, $this->iv);
    }
}