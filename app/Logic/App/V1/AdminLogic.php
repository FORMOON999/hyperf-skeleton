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

namespace App\Logic\App\V1;

use App\Common\Core\Entity\BaseSuccessResponse;
use App\Entity\Request\App\V1\Admin\AdminCreateRequest;
use App\Entity\Request\App\V1\Admin\AdminDetailRequest;
use App\Entity\Request\App\V1\Admin\AdminListRequest;
use App\Entity\Request\App\V1\Admin\AdminModifyRequest;
use App\Entity\Request\App\V1\Admin\AdminRemoveRequest;
use App\Entity\Response\App\V1\Admin\AdminDetailResponse;
use App\Entity\Response\App\V1\Admin\AdminListResponse;
use App\Model\Admin;
use Hyperf\Di\Annotation\Inject;

class AdminLogic
{

    #[Inject]
    protected Admin $admin;

    public function getList(AdminListRequest $request): AdminListResponse
    {
        $query = $this->admin->buildQuery($request->condition, $request->search, $request->sort);
        $result = $this->admin->output($query, $request->page);
        return new AdminListResponse($result);
    }

    public function create(AdminCreateRequest $request): BaseSuccessResponse
    {
        $this->admin->createByCondition($request->condition, $request->data);
        return new BaseSuccessResponse();
    }

    public function modify(AdminModifyRequest $request): BaseSuccessResponse
    {
        $this->admin->modifyByCondition($request->condition, $request->search, $request->data);
        return new BaseSuccessResponse();
    }

    public function remove(AdminRemoveRequest $request): BaseSuccessResponse
    {
        $this->admin->removeByCondition($request->condition, $request->search);
        return new BaseSuccessResponse();
    }

    public function detail(AdminDetailRequest $request): AdminDetailResponse
    {
        $query = $this->admin->buildQuery($request->condition, $request->search);
        return $query->first();
    }
}
