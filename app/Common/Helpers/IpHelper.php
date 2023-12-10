<?php
/**
 * Created by PhpStorm.
 * Date:  2021/11/25
 * Time:  10:30 下午.
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

namespace App\Common\Helpers;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * ip info.
 */
class IpHelper
{
    #[Inject]
    protected ClientFactory $clientFactory;

    #[Inject]
    protected ServerRequestInterface $request;

    /**
     * 真实 ip.
     */
    public function getClientIp(string $headerName = 'x-real-ip'): string
    {
        $client = $this->request->getServerParams();
        $xri = $this->request->getHeader($headerName);
        if (! empty($xri)) {
            $clientAddress = $xri[0];
        } else {
            $clientAddress = $client['remote_addr'];
        }
        $xff = $this->request->getHeader('x-forwarded-for');
        if ($clientAddress === '127.0.0.1') {
            if (! empty($xri)) {
                // 如果有xri 则判定为前端有NGINX等代理
                $clientAddress = $xri[0];
            } elseif (! empty($xff)) {
                // 如果不存在xri 则继续判断xff
                $list = explode(',', $xff[0]);
                if (isset($list[0])) {
                    $clientAddress = $list[0];
                }
            }
        }
        return $clientAddress;
    }
}
