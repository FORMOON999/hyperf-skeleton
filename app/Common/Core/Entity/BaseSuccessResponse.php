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

namespace App\Common\Core\Entity;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

class BaseSuccessResponse
{
    #[ApiModelProperty('请求结果')]
    public string $result;

    public function __construct($data = '1')
    {
        $this->result = (string) $data;
    }
}
