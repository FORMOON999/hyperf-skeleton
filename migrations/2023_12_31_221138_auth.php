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

use App\Common\Traits\MigrateFiledTrait;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class Auth extends Migration
{
    use MigrateFiledTrait;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('role')) {
            Schema::create('role', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('角色管理');
                $table->string('name', 32)->comment('角色名称');
                $table->string('code', 32)->comment('角色编码');
                $table->integer('sort')->default(1)->comment('排序');
                $table->tinyInteger('status')->comment('状态');
            });
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
        }

        if (! Schema::hasTable('role_menu')) {
            Schema::create('role_menu', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('角色菜单关联');
                $table->integer('role_id')->default(0)->comment('角色ID');
                $table->integer('menu_id')->default(0)->comment('菜单ID');
            });
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
