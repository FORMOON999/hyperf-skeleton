<?php
declare(strict_types=1);

namespace App\Controller\App\V1;

use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiHeader;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\PostMapping;
use Lengbin\Helper\YiiSoft\Arrays\ArrayHelper;
use App\Common\BaseController;
use App\Common\Helpers\ConstantOutputHelper;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

#[Controller(prefix: "api/v1/app/admin/constant")]
#[Api(tags: "App/管理员常量管理")]
#[Middleware(AppMiddleware::class)]
#[ApiHeader(name: 'Authorization')]
class ConstantController extends BaseController
{
    protected array $enum = [];

    protected array $error = [];

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        $path = BASE_PATH . "/app/Constants";
        $constants = $container->get(ConstantOutputHelper::class)->scan($path);
        $this->error = ArrayHelper::remove($constants, 'errors');
        $this->enum = $constants;
    }

    #[PostMapping(path: "enums")]
    #[ApiOperation("获取枚举")]
    public function enums(): ResponseInterface
    {
        return $this->response->success($this->enum);
    }

    #[PostMapping(path: "errors")]
    #[ApiOperation("获取错误码")]
    public function errors(): ResponseInterface
    {
        return $this->response->success($this->error);
    }
}