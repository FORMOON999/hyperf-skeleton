<?php
/**
 * Created by PhpStorm.
 * Date:  2021/9/3
 * Time:  1:20 上午.
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

namespace App\Common;

use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Lengbin\Common\Entity\Page;

class BaseMySQLDao
{
    public function count(Builder $query): int
    {
        $sql = sprintf('select count(*) as count from (%s) as b', $query->toSql());
        return Db::selectOne($sql, $query->getBindings())->count;
    }

    public function output(Builder $query, Page $page): array
    {
        $output = [
            'total' => null,
            'page' => null,
            'page_size' => null,
        ];

        if ($page->total) {
            $output['total'] = $this->count($query);
        }

        if (! $page->all) {
            $query->forPage($page->page, $page->pageSize);
            $output['page'] = $page->page;
            $output['page_size'] = $page->pageSize;
        }

        $output['list'] = $query->get()->toArray();
        return $output;
    }

    /**
     * @param array $data [start, end]
     */
    public function betweenTime(Builder $query, string $field, array $data): Builder
    {
        $query->where(function (Builder $builder) use ($field, $data) {
            if ($data['start'] > 0) {
                $builder->where($field, '>=', $data['start']);
            }
            if ($data['end'] > 0) {
                $builder->where($field, '<', $data['end']);
            }
        });

        return $query;
    }
}
