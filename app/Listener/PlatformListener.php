<?php
/**
 * Created by PhpStorm.
 * Date:  2022/4/14
 * Time:  5:02 PM.
 */

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Listener;

use App\Common\Exceptions\BusinessException;
use App\Common\Helpers\IpHelper;
use App\Constants\Errors\PlatformError;
use App\Event\PlatformLoginEvent;
use App\Infrastructure\PlatformInterface;
use App\Infrastructure\PlatformLoginRecordInterface;
use Hyperf\DbConnection\Annotation\Transactional;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Snowflake\IdGenerator\SnowflakeIdGenerator;

#[Listener]
class PlatformListener implements ListenerInterface
{
    #[Inject()]
    protected IpHelper $ipHelper;

    #[Inject()]
    protected PlatformInterface $platform;

    #[Inject()]
    protected PlatformLoginRecordInterface $platformLoginRecord;

    #[Inject()]
    protected SnowflakeIdGenerator $idGenerator;

    public function listen(): array
    {
        return [
            PlatformLoginEvent::class,
        ];
    }

    #[Transactional()]
    public function process(object $event): void
    {
        if ($event instanceof PlatformLoginEvent) {
            // 最新的登录时间
            $platform = $this->platform->modify([
                'id' => $event->platformId,
            ], [
                'last_time' => date('Y-m-d H:i:s'),
            ]);
            if (!$platform) {
                throw new BusinessException(PlatformError::UPDATE_ERROR());
            }

            // 登录日志
            $ip = $this->ipHelper->getClientIp();
            [$address, $address2, $address3] = ['未知', '未知', '未知'];
            $ret = $this->platformLoginRecord->create([
                'platform_id' => $event->platformId,
                'ip' => $ip,
                'platform_login_record_id' => $this->idGenerator->generate(),
                'address' => $address,
                'address2' => $address2,
                'address3' => $address3,
            ]);
            if (!$ret) {
                throw new BusinessException(PlatformError::UPDATE_ERROR());
            }
        }
    }
}
