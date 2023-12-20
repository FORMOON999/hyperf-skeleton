<?php

namespace App\Controller\Platform\V1;

use App\Common\Core\BaseController;

#[Controller(prefix: 'api/v1/platform/platform')]
#[Api(tags: 'Platform/管理台/管理台管理')]
#[Middleware(PlatformMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class LoginController extends BaseController
{

}