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

namespace App\Common\Core;

use App\Common\Core\Entity\Output;
use App\Common\Core\Entity\Page;
use Hyperf\Codec\Json;
use Hyperf\Collection\Collection;
use Hyperf\HttpServer\Exception\Http\EncodingException;
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

    public function outputForArray(array $data, ?Page $page = null): Output
    {
        $output = new Output();
        if (is_null($page)) {
            $output->list = $data;
            return $output;
        }

        $pageSize = $page->pageSize;
        $offset = ($page->page - 1) * $pageSize;
        $data = array_values($data);
        $output->page = $page->page;
        $output->pageSize = $pageSize;
        $output->list = array_slice($data, $offset, $pageSize);

        return $output;
    }

    public function outputForCollection(Collection $result, ?Page $page = null): Output
    {
        $output = new Output();
        if (is_null($page)) {
            $output->list = $result->map(function ($item) {
                return $item instanceof BaseModel ? $item->newEntity() : $item;
            })->toArray();
            return $output;
        }

        $pageSize = $page->pageSize;
        $offset = ($page->page - 1) * $pageSize;
        $output->page = $page->page;
        $output->pageSize = $pageSize;
        $output->list = $result->slice($offset, $pageSize)->map(function ($item) {
            return $item instanceof BaseModel ? $item->newEntity() : $item;
        })->toArray();
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
