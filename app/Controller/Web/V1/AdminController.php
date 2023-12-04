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

namespace App\Controller\Web\V1;

use App\Common\Core\BaseController;
use App\Common\Core\Entity\BaseCondition;
use App\Model\Admin;
use App\Model\AdminEntity;
use Hyperf\ApiDocs\Annotation\Api;
use Hyperf\ApiDocs\Annotation\ApiOperation;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Lengbin\Common\Entity\Page;

#[Controller(prefix: 'api/v1/web/admin')]
#[Api(tags: 'Web/管理台/管理用户管理')]
class AdminController extends BaseController
{
    #[ApiOperation('list')]
    #[GetMapping(path: '')]
    public function getList()
    {
        $admin = new Admin();
        //        $admin->fill([
        //            "username" => "1",
        //            "password" => "1",
        //            "nickname" => "1"
        //        ]);
        //        $status = $admin->save();

        //        $admin = Admin::query()->find(1);
        //        $admin->fill([
        //            "username" => "2",
        //            "password" => "1",
        //            "nickname" => "1"
        //        ]);
        //        $status = $admin->save();
        //        $status = $admin->forceDelete();
        //        return [$status];


        $condition = new BaseCondition();

//        $adminEntity = new AdminEntity([
//            "username" => "2",
//            "password" => "1",
//        ]);
//        $data = $admin->createByCondition($condition, $adminEntity);

        $query = $admin->buildQuery($condition);
//        $data = $admin->output($query, Page::all());
        $data = $query->where('id', 1)->first();
        var_dump($data);
        return [
            $data
        ];
    }
}
