<?php

namespace App\Controller\Web\V1;

use App\Common\Core\BaseController;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

#[Controller(prefix: 'api/v1/web/admin')]
#[Api(tags: 'Web/管理台/管理用户管理')]
class AdminController extends BaseController
{
    #[ApiOperation('list')]
    #[GetMapping(path: '')]
    public function getList()
    {
        return [];
    }
}