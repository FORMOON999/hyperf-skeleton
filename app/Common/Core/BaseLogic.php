<?php

declare(strict_types=1);

namespace App\Common\Core;

use Hyperf\Codec\Json;
use Hyperf\HttpServer\Exception\Http\EncodingException;
use Lengbin\Common\Entity\Page;
use Throwable;

class BaseLogic extends BaseService
{
    /**
     * page.
     */
    public function outputForArray(array $data, Page $page): array
    {
        $output = [];
        if ($page->total) {
            $total = count($data);
            $output['total'] = $total;
        }

        $list = $data;
        if (! $page->all) {
            $pageSize = $page->pageSize;
            $offset = ($page->page - 1) * $pageSize;
            $data = array_values($data);
            $output['page'] = $page->page;
            $output['page_size'] = $pageSize;
            $list = array_slice($data, $offset, $pageSize);
        }

        $output['list'] = $list;
        return $output;
    }

    public function toJson($data): string
    {
        try {
            $result = Json::encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (Throwable $exception) {
            throw new EncodingException($exception->getMessage(), $exception->getCode());
        }

        return $result;
    }
}