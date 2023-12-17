<?php

declare(strict_types=1);

namespace App\Common\Util\Auth\Mode;

use App\Common\Util\Auth\JwtHelper;
use App\Common\Util\Auth\JwtSubject;
use App\Common\Util\Auth\LoginInterface;
use HyperfExt\Jwt\Contracts\StorageInterface;
use HyperfExt\Jwt\Storage\HyperfCache;

class TokenMode implements LoginInterface
{

    protected JwtHelper $jwtHelper;

    protected ?StorageInterface $storage = null;

    public function __construct(JwtHelper $jwtHelper)
    {
        $this->jwtHelper = $jwtHelper;
    }

    public function makeToke(string $sub, ?string $iss = null, array $data = []): string
    {
        $data['sub'] = $sub;
        if ($iss) {
            $data['iss'] = $iss;
        }
        $token = $this->jwtHelper->make($data);
        $this->refreshToken($token);
        return $token;
    }

    protected function getKey(array $result)
    {
        $isOss = \Hyperf\Config\config('auth.oss', false);
        return $isOss ? $result['sub'] : $result['jti'];
    }

    protected function handle(string $token, string $event, array $result = []): void
    {
        if (empty($result)) {
            $result = $this->jwtHelper->getManager()->getCodec()->decode($token);
        }
        $key = $this->getKey($result);
        switch ($event) {
            case "add":
                $this->getStorage()->add($key, $token, $this->getTtl());
                break;
            case "remove":
                $this->getStorage()->destroy($key);
                break;
        }
    }

    public function logout(string $token): bool
    {
        $this->handle($token, 'remove');
        return true;
    }

    public function refreshToken(string $token): string
    {
        $this->handle($token, 'add');
        return '';
    }

    public function verifyToken(?string $token, bool $ignoreExpired = false): JwtSubject
    {
        $payload = new JwtSubject();
        if (empty($token)) {
            $payload->invalid = true;
            return $payload;
        }
        $result = $this->jwtHelper->getManager()->getCodec()->decode($token);
        $key = $this->getKey($result);
        $cacheToken = $this->getStorage()->get($key);
        if (!empty($cacheToken)) {
            if ($cacheToken !== $token) {
                $payload->invalid = true;
            }
        } else {
            $payload->expired = true;
        }

        if (!$payload->expired && !$payload->invalid) {
            $this->handle($token, 'add', $result);
            $defaultClaims = $this->jwtHelper->getManager()->getPayloadFactory()->getDefaultClaims();
            foreach ($defaultClaims as $claim) {
                if ($claim === 'iss') {
                    continue;
                }
                unset($result[$claim]);
            }
            $payload->data = $result;
        }
        return $payload;
    }

    public function getTtl(): int
    {
        return $this->jwtHelper->getTtl();
    }

    public function getStorage(): StorageInterface
    {
        if (is_null($this->storage)) {
            $storageClass = \Hyperf\Config\config('jwt.blacklist_storage', HyperfCache::class);
            $this->storage = \Hyperf\Support\make($storageClass, [
                'tag' => 'jwt.token',
            ]);
        }
        return $this->storage;
    }
}