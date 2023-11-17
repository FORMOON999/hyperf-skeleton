<?php

namespace App\Controller\Web\V1;

use App\Common\BaseController;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\HttpServer\Annotation\Controller;

#[Controller(prefix: 'api/v1/web/admin')]
#[Api(tags: 'Web/管理台/管理用户管理')]
class AdminController extends BaseController
{
    public function getList()
    {

    }
}