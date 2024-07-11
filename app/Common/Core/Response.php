<?php
/**
 * Created by PhpStorm.
 * Date:  2021/9/3
 * Time:  10:55 上午.
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

namespace App\Common\Core;

use App\Common\Core\Enum\CommonError;
use Hyperf\Codec\Json;
use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Hyperf\HttpMessage\Cookie\Cookie;
use Hyperf\HttpMessage\Stream\SwooleFileStream;
use Hyperf\HttpServer\Exception\Http\EncodingException;
use Hyperf\HttpServer\Exception\Http\FileException;
use Hyperf\Support\MimeTypeExtensionGuesser;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use SplFileInfo;
use stdClass;
use Throwable;

class Response extends \Hyperf\HttpServer\Response
{
    public function success($data = [], string $msg = ''): PsrResponseInterface
    {
        return $this->json([
            'code' => CommonError::SUCCESS,
            'msg' => $msg,
            'data' => empty($data) ? new stdClass() : $data,
        ]);
    }

    public function fail($code, $message = '', $status = 200): PsrResponseInterface
    {
        return $this->json([
            'code' => $code,
            'msg' => $message,
            'data' => new stdClass(),
        ])->withStatus($status);
    }

    public function cookie(Cookie $cookie)
    {
        $response = $this->withCookie($cookie);
        Context::set(PsrResponseInterface::class, $response);
        return $this;
    }

    public function header(string $name, string $value)
    {
        $response = $this->withAddedHeader($name, $value);
        Context::set(PsrResponseInterface::class, $response);
        return $this;
    }

    public function fileFlow(string $file)
    {
        $file = new SplFileInfo($file);
        if (! $file->isReadable()) {
            throw new FileException('File must be readable.');
        }
        $contentType = \Hyperf\Support\value(function () use ($file) {
            $mineType = null;
            if (ApplicationContext::hasContainer()) {
                $guesser = ApplicationContext::getContainer()->get(MimeTypeExtensionGuesser::class);
                $mineType = $guesser->guessMimeType($file->getExtension());
            }
            return $mineType ?? 'application/octet-stream';
        });

        return $this->getResponse()
            ->withAddedHeader('content-type', $contentType)
            ->withBody(new SwooleFileStream($file));
    }

    protected function toJson($data): string
    {
        try {
            $result = Json::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Throwable $exception) {
            throw new EncodingException($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }
}
