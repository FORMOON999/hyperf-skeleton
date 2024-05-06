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

namespace App\Common\Commands\CodeGenerator;

use App\Common\Commands\CodeGenerator\Generator\ConstantControllerGenerator;
use App\Common\Commands\CodeGenerator\Generator\ControllerGenerator;
use App\Common\Commands\CodeGenerator\Generator\DaoGenerator;
use App\Common\Commands\CodeGenerator\Generator\DaoInterfaceGenerator;
use App\Common\Commands\CodeGenerator\Generator\ErrorGenerator;
use App\Common\Commands\CodeGenerator\Generator\LogicGenerator;
use App\Common\Commands\CodeGenerator\Generator\ModelGenerator;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorCondition;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorCreateData;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorCreateRequest;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorDetailRequest;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorListRequest;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorListSearch;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorListSort;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorModifyData;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorModifyRequest;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorRemoveRequest;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorRemoveSearch;
use App\Common\Commands\CodeGenerator\Generator\Request\GeneratorSearch;
use App\Common\Commands\CodeGenerator\Generator\Response\GeneratorDetailResponse;
use App\Common\Commands\CodeGenerator\Generator\Response\GeneratorListItem;
use App\Common\Commands\CodeGenerator\Generator\Response\GeneratorListResponse;
use App\Common\Commands\CodeGenerator\Generator\ServiceGenerator;
use App\Common\Commands\CodeGenerator\Generator\ServiceInterfaceGenerator;
use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Dag\Dag;
use Hyperf\Dag\Vertex;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Input\InputOption;

/*x
 * @Command
 */
#[Command]
class GenerateCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('gen:code');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Generate code file Command');

        $this->addOption('path', 'p', InputOption::VALUE_REQUIRED, '路径', '/app');
        $this->addOption('path_version', 'pv', InputOption::VALUE_REQUIRED, '版本', 'v1');
        $this->addOption('pool', 'P', InputOption::VALUE_REQUIRED, '数据连接池', 'default');
        $this->addOption('table', 't', InputOption::VALUE_OPTIONAL, '表');
        $this->addOption('url', 'u', InputOption::VALUE_REQUIRED, '请求url前缀', '/api');
        $this->addOption('applications', 'a', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, '应用端', []);
        $this->addOption('for_table_ddd', 'ddd', InputOption::VALUE_OPTIONAL, '根据表名区分模块');
        $this->addOption('force', 'f', InputOption::VALUE_NEGATABLE, '强制创建');
    }

    public function handle()
    {
        $this->line('代码自动生成工具启动', 'info');

        $applications = $this->input->getOption('applications');
        if (empty($applications)) {
            $applications = \Hyperf\Config\config('generate.applications');
        }

        if (empty($applications)) {
            $this->alert('请设置应用端');
            return;
        }

        $config = new GeneratorConfig();
        $config->applications = $applications;
        $config->path = $this->input->getOption('path');
        $config->version = $this->input->getOption('path_version');
        $config->url = $this->input->getOption('url');

        $pool = $this->input->getOption('pool');
        $table = $this->input->getOption('table');
        $ddd = $this->input->getOption('for_table_ddd');
        if (is_null($ddd)) {
            $ddd = \Hyperf\Config\config('generate.for_table_ddd');
        }

        $this->process($config, $pool, $table, $ddd);

        $this->line('代码自动生成工具完成', 'info');
    }

    public function process(GeneratorConfig $config, string $pool = 'default', ?string $table = null, bool $ddd = false): void
    {
        $force = $this->input->getOption('force');
        // model 生成
        $models = (new ModelGenerator($this->container, $ddd))->generate($pool, $table, $force ?? false);
        $modules = \Hyperf\Config\config('generate.modules', []);
        if (empty($modules)) {
            foreach ($models as $model) {
                if (! in_array($model->module, $modules)) {
                    $modules[] = $model->module;
                }
            }
        }

        foreach ($models as $model) {
            $condition = [
                'modelInfo' => $model,
                'config' => $config,
                'ddd' => $ddd,
            ];
            $getListRequest = $this->getListRequest($condition);
            $createRequest = $this->createRequest($condition);
            $modifyRequest = $this->modifyRequest($condition);
            $detailRequest = $this->detailRequest($condition);
            $removeRequest = $this->removeRequest($condition);

            $getListResponse = $this->getListResponse($condition);
            $detailResponse = $this->detailResponse($condition);

            $dag = new Dag();
            $serviceInterface = Vertex::of(new ServiceInterfaceGenerator($condition), 'serviceInterface');
            $service = Vertex::of(new ServiceGenerator($condition), 'service');
            $error = Vertex::of(new ErrorGenerator(array_merge($condition, [
                'moduleIndex' => array_search($model->module, $modules) + 1,
            ])), 'error');

//            $logic = Vertex::of(new LogicGenerator($condition), 'logic');
            $controller = Vertex::of(new ControllerGenerator($condition), 'controller');

            $dag->addVertex($error)
                ->addVertex($serviceInterface)
                ->addVertex($service)
//                ->addVertex($logic)
                ->addVertex($controller)
                ->addVertex($getListRequest)
                ->addVertex($createRequest)
                ->addVertex($modifyRequest)
                ->addVertex($detailRequest)
                ->addVertex($removeRequest)
                ->addVertex($getListResponse)
                ->addVertex($detailResponse)
                ->addEdge($serviceInterface, $service)
                ->addEdge($error, $controller)
                ->addEdge($getListRequest, $controller)
                ->addEdge($createRequest, $controller)
                ->addEdge($modifyRequest, $controller)
                ->addEdge($detailRequest, $controller)
                ->addEdge($removeRequest, $controller)
                ->addEdge($getListResponse, $controller)
                ->addEdge($detailResponse, $controller)
                ->addEdge($service, $controller)
//                ->addEdge($logic, $controller)
                ->run();
        }
    }

    protected function getListRequest(array $condition): Vertex
    {
        $requestListSearch = Vertex::of(new GeneratorListSearch($condition), 'requestListSearch');
        $requestList = Vertex::of(new GeneratorListRequest($condition), 'requestList');
        $dagRequestList = new Dag();
        $dagRequestList
            ->addVertex($requestListSearch)
            ->addVertex($requestList)
            ->addEdge($requestListSearch, $requestList);
        return Vertex::of($dagRequestList, 'entity_list');
    }

    protected function createRequest(array $condition): Vertex
    {
        $requestCreate = Vertex::of(new GeneratorCreateRequest($condition), 'requestCreate');
        $dagRequestCreate = new Dag();
        $dagRequestCreate->addVertex($requestCreate);
        return Vertex::of($dagRequestCreate, 'entity_create');
    }

    protected function modifyRequest(array $condition): Vertex
    {
        $requestModify = Vertex::of(new GeneratorModifyRequest($condition), 'requestModify');
        $dagRequestModify = new Dag();
        $dagRequestModify->addVertex($requestModify);
        return Vertex::of($dagRequestModify, 'entity_modify');
    }

    protected function detailRequest(array $condition): Vertex
    {
        $requestDetail = Vertex::of(new GeneratorDetailRequest($condition), 'requestDetail');
        $dagRequestDetail = new Dag();
        $dagRequestDetail->addVertex($requestDetail);
        return Vertex::of($dagRequestDetail, 'entity_detail');
    }

    protected function removeRequest(array $condition): Vertex
    {
        $requestRemove = Vertex::of(new GeneratorRemoveRequest($condition), 'requestRemove');
        $dagRequestRemove = new Dag();
        $dagRequestRemove->addVertex($requestRemove);
        return Vertex::of($dagRequestRemove, 'entity_remove');
    }

    protected function getListResponse(array $condition): Vertex
    {
        $responseList = Vertex::of(new GeneratorListResponse($condition), 'responseList');
        $dagResponseList = new Dag();
        $dagResponseList->addVertex($responseList);
        return Vertex::of($dagResponseList, 'entity_list_response');
    }

    protected function detailResponse(array $condition): Vertex
    {
        $dagResponseDetail = new Dag();
        $responseDetail = Vertex::of(new GeneratorDetailResponse($condition), 'responseDetail');
        $dagResponseDetail->addVertex($responseDetail);
        return Vertex::of($dagResponseDetail, 'entity_item');
    }
}
