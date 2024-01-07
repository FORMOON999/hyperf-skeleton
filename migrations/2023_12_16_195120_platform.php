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

class Platform extends Migration
{
    use MigrateFiledTrait;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('platform')) {
            Schema::create('platform', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('管理员');

                $table->string('username', 32)->comment('账号');
                $table->string('nickname', 32)->comment('昵称');
                $table->string('password', 64)->comment('密码');
                $table->json('roles')->comment('角色');
                $table->tinyInteger('status')->comment('状态');
                $table->dateTime('last_time')->nullable()->comment('上次登录时间');
            });

            $date = date('Y-m-d H:i:s');
            \Hyperf\DbConnection\Db::table('platform')->insert([
                'username' => 'admin',
                'nickname' => 'admin',
                'roles' => json_encode(['ADMIN']),
                'password' => \Lengbin\Helper\Util\PasswordHelper::generatePassword('123456'),
                'status' => \App\Common\Constants\BaseStatus::NORMAL,
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
        if (! Schema::hasTable('platform_login_record')) {
            Schema::create('platform_login_record', function (Blueprint $table) {
                $this->commonFields($table);
                $table->comment('管理员登录日志');

                $table->integer('platform_id')->default(0)->comment('管理台id');
                $table->string('ip', 16)->comment('ip');
                $table->string('address', 255)->default('未知')->comment('地址');
                $table->string('address1', 255)->default('未知')->comment('地址1');
                $table->string('address2', 255)->default('未知')->comment('地址2');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'platform',
            'platform_login_record',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
