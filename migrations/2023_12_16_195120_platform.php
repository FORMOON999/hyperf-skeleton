<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class Init extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('platform')) {
            Schema::create('platform', function (Blueprint $table) {
                $table->increments("id");
                $table->comment('管理台');
                $table->dateTime("created_at")->comment("创建时间");
                $table->dateTime("updated_at")->comment("更新时间");
                $table->dateTime("deleted_at")->comment("删除时间");

                $table->string("username", 32)->comment("账号");
                $table->string("nickname", 32)->comment("昵称");
                $table->string("password", 64)->comment("密码");
                $table->tinyInteger("status")->comment("状态");
            });
        }
        if (!Schema::hasTable('platform_login_record')) {
            Schema::create('platform_login_record', function (Blueprint $table) {
                $table->increments("id");
                $table->comment('管理台登录日志');
                $table->dateTime("created_at")->comment("创建时间");
                $table->dateTime("updated_at")->comment("更新时间");
                $table->dateTime("deleted_at")->comment("删除时间");

                $table->integer("platform_id")->default(0)->comment("管理台id");
                $table->string("ip", 16)->comment("ip");
                $table->string("address", 255)->default("未知")->comment("地址");
                $table->string("address1", 255)->default("未知")->comment("地址1");
                $table->string("address2", 255)->default("未知")->comment("地址2");
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            "platform",
            "platform_login_record"
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
