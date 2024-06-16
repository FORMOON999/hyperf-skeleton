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
use App\Common\Constants\BaseStatus;
use App\Common\Traits\MigrateFiledTrait;
use App\Constants\Type\MenuType;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

class Auth extends Migration
{
    use MigrateFiledTrait;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $date = date('Y-m-d H:i:s');
        if (! Schema::hasTable('role')) {
            Schema::create('role', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('角色管理');
                $table->string('name', 32)->comment('角色名称');
                $table->string('code', 32)->comment('角色编码');
                $table->integer('sort')->default(1)->comment('排序');
                $table->tinyInteger('status')->comment('状态');
            });

            Db::table('role')->insert([
                'name' => '系统管理员',
                'code' => 'ADMIN',
                'sort' => 1,
                'status' => BaseStatus::NORMAL,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }

        if (! Schema::hasTable('menu')) {
            Schema::create('menu', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('菜单管理');
                $table->integer('pid')->default(0)->comment('父级');
                $table->string('name', 32)->comment('菜单名称');
                $table->tinyInteger('type')->default(1)->comment('菜单类型(1-菜单；2-目录；3-外链；4-按钮权限)');
                $table->string('path', 255)->comment('路由路径');
                $table->string('component', 255)->comment('组件路径(vue页面完整路径，省略.vue后缀)');
                $table->string('perm', 255)->comment('权限标识');
                $table->integer('sort')->default(1)->comment('排序');
                $table->tinyInteger('status')->comment('状态');
                $table->string('icon', 255)->comment('菜单图标');
                $table->string('redirect', 255)->comment('跳转路径');
            });
            Db::table('menu')->insert([
                ['id' => 1, 'pid' => 0, 'name' => '系统管理', 'type' => MenuType::CATALOG, 'path' => '/system', 'component' => 'Layout', 'perm' => '', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => 'system', 'redirect' => '/system/platform', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 2, 'pid' => 1, 'name' => '管理员管理', 'type' => MenuType::MENU, 'path' => 'platform', 'component' => 'system/platform/index', 'perm' => '', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => 'platform', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 3, 'pid' => 2, 'name' => '管理员新增', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'platform:add', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 4, 'pid' => 2, 'name' => '管理员编辑', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'platform:edit', 'sort' => 2, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 5, 'pid' => 2, 'name' => '管理员删除', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'platform:remove', 'sort' => 3, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 6, 'pid' => 1, 'name' => '角色管理', 'type' => MenuType::MENU, 'path' => 'role', 'component' => 'system/role/index', 'perm' => '', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => 'role', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 7, 'pid' => 6, 'name' => '角色新增', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'role:add', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 8, 'pid' => 6, 'name' => '角色编辑', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'role:edit', 'sort' => 2, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 9, 'pid' => 6, 'name' => '角色删除', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'role:remove', 'sort' => 3, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 10, 'pid' => 1, 'name' => '菜单管理', 'type' => MenuType::MENU, 'path' => 'menu', 'component' => 'system/menu/index', 'perm' => '', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => 'menu', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 11, 'pid' => 10, 'name' => '菜单新增', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'menu:add', 'sort' => 1, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 12, 'pid' => 10, 'name' => '菜单编辑', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'menu:edit', 'sort' => 2, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
                ['id' => 13, 'pid' => 10, 'name' => '菜单删除', 'type' => MenuType::BUTTON, 'path' => '', 'component' => '', 'perm' => 'menu:remove', 'sort' => 3, 'status' => BaseStatus::NORMAL, 'icon' => '', 'redirect' => '', 'created_at' => $date, 'updated_at' => $date],
            ]);
        }

        if (! Schema::hasTable('role_menu')) {
            Schema::create('role_menu', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('角色菜单关联');
                $table->integer('role_id')->default(0)->comment('角色ID');
                $table->integer('menu_id')->default(0)->comment('菜单ID');
            });
            //            Db::table('role_menu')->insert([
            //                ['role_id' => 1, 'menu_id' => 1, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 2, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 3, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 4, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 5, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 6, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 7, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 8, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 9, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 10, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 11, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 12, 'created_at' => $date, 'updated_at' => $date],
            //                ['role_id' => 1, 'menu_id' => 13, 'created_at' => $date, 'updated_at' => $date],
            //            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'role',
            'menu',
            'role_menu',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
