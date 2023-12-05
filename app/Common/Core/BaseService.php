<?php
/**
 * Created by PhpStorm.
 * Date:  2021/9/3
 * Time:  1:17 上午.
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

use App\Common\Core\Entity\OutputResult;
use Hyperf\Codec\Json;
use Hyperf\HttpServer\Exception\Http\EncodingException;
use Lengbin\Common\Entity\Page;
use Throwable;

class BaseService
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

    public function outputForArray(array $data, Page $page): array
    {
        $output = [];
        if ($page->total) {
            $output['total'] = count($data);
        }

        $list = $data;
        if (! $page->all) {
            $pageSize = $page->pageSize;
            $offset = ($page->page - 1) * $pageSize;
            $data = array_values($data);
            $output['page'] = $page->page;
            $output['pageSize'] = $pageSize;
            $list = array_slice($data, $offset, $pageSize);
        }

        $output['list'] = $list;
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
