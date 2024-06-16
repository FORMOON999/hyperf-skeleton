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
use App\Common\Helpers\PasswordHelper;
use App\Common\Traits\MigrateFiledTrait;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;
use Hyperf\DbConnection\Db;

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
                $table->string('avatar', 255)->comment('头像');
                $table->json('roles')->comment('角色');
                $table->tinyInteger('status')->comment('状态');
                $table->dateTime('last_time')->nullable()->comment('上次登录时间');
            });

            $date = date('Y-m-d H:i:s');
            Db::table('platform')->insert([
                'username' => 'admin',
                'nickname' => 'admin',
                'roles' => json_encode(['ADMIN']),
                'password' => PasswordHelper::generatePassword('123456'),
                'status' => BaseStatus::NORMAL,
                'created_at' => $date,
                'updated_at' => $date,
                'avatar' => 'https://oss.youlai.tech/youlai-boot/2023/05/16/811270ef31f548af9cffc026dfc3777b.gif',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'platform',
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
}
