<?php

namespace App\Common\Core;

use App\Common\Core\Entity\Output;
use Hyperf\Codec\Json;
use Hyperf\HttpServer\Exception\Http\EncodingException;
use Lengbin\Common\Entity\Page;
use Throwable;

trait ServiceTrait
{
    public function toArray(mixed $data, callable $handler)
    {
        if (is_object($data)) {
            return call_user_func($handler, $data);
        }

        foreach ($data as $key => $item) {
            $data[$key] = call_user_func($handler, $item);
        }
        return $data;
    }

    public function outputForArray(array $data, Page $page): Output
    {
        $output = new Output();
        if ($page->total) {
            $output->total = count($data);
        }

        $list = $data;
        if (! $page->all) {
            $pageSize = $page->pageSize;
            $offset = ($page->page - 1) * $pageSize;
            $data = array_values($data);
            $output->page = $page->page;
            $output->pageSize = $pageSize;
            $list = array_slice($data, $offset, $pageSize);
        }

        $output->list = $list;
        return $output;
    }

    public function toJson(mixed $data): string
    {
        try {
            $result = Json::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Throwable $exception) {
            throw new EncodingException($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }
}