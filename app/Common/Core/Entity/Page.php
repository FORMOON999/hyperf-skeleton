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

namespace App\Common\Core\Entity;

use App\Common\Core\BaseObject;
use App\Common\Helpers\Arrays\ArrayHelper;
use App\Common\Helpers\FormatHelper;
use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class Page extends BaseObject
{
    #[ApiModelProperty('页码')]
    public ?int $page = null;

    #[ApiModelProperty('页数')]
    public ?int $pageSize = null;

    #[ApiModelProperty('排序')]
    public ?string $sort = null;

    /**
     * sort = ""  =>  ['id' => 'desc']
     * sort = "id"  =>  ['id' => 'asc']
     * sort = "-id"  =>  ['id' => 'desc']
     * sort = "id, name"  =>  ['id' => 'asc', 'name' => 'asc']
     * sort = "-id, name"  =>  ['id' => 'desc', 'name' => 'asc'].
     * @return array|string[]
     */
    public function getSort(): array
    {
        if (empty($this->sort)) {
            return ['id' => 'desc'];
        }

        $sorts = explode(',', $this->sort);
        $result = [];
        foreach ($sorts as $sort) {
            $sort = trim($sort);
            $sort = FormatHelper::uncamelize($sort);
            if (str_contains($sort, '-')) {
                $sort = str_replace('-', '', $sort);
                $result[$sort] = 'desc';
            } else {
                $result[$sort] = 'asc';
            }
        }
        return $result;
    }

    public function getPage(): array
    {
        $result = [];
        if ($this->page > 0) {
            $result['page'] = $this->page;
        }
        if ($this->pageSize > 0) {
            $result['pageSize'] = $this->pageSize;
        }
        return $result;
    }

    public function getSearchParams(): array
    {
        $params = $this->setUnderlineName()?->toArray() ?? [];
        if (empty($params)) {
            return [];
        }
        return ArrayHelper::unset($params, ['page', 'page_size', 'sort']);
    }
}
