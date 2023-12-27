<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\ApiDocs\Annotation\ApiModelProperty;

/**
 * Class PlatformLoginRecordEntity
 * @package App\Model
 */
class PlatformLoginRecordEntity extends \App\Common\Core\Entity\BaseModelEntity
{

    #[ApiModelProperty("管理台id")]
    public int $platformId;

    #[ApiModelProperty("ip")]
    public string $ip;

    #[ApiModelProperty("地址")]
    public string $address;

    #[ApiModelProperty("地址1")]
    public string $address1;

    #[ApiModelProperty("地址2")]
    public string $address2;

    #[ApiModelProperty('管理员')]
    public PlatformEntity $platform;
}