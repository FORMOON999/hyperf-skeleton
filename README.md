> 基于 php8.0 hyperf3.0 框架封装的骨架

接口 我也经历了几个版本

- get，post
- restful api接口规范
- 万能 post

当前这个骨架 就是用 万能post，  如果不喜欢 或者不习惯，通过代码生成后， 去控制把请求方式修改一下就行了， 对吧。


# 如何使用

## 管理台
请移驾到 [vue3-element-admin](https://github.com/youlaitech/vue3-element-admin)

## 安装

```php
git clone
```
> 当前这个骨架 是 hyperf 3.0 版本， php8

## 代码生成器命令（记得修改数据库配置）

```php
php bin/hyperf.php gen:code
```

> 1，代码生成器是基于数据库表来实现的， 所以执行此命名之前，先确认一下 数据库 是否创建了表。如果想使用我二次封装的model,在表里面需要  添加至少 3个字段
>
> - enable  是否能使用（类似软删字段）默认为1
> - create_at  创建时间戳，默认为0
> - update_at 更新时间戳，默认为0
>
> 2，生成器配置文件在 `config/autoload/generate.php`
>
> ```php
> return [
>     // 定义应用端
>     'applications' => [
>         'api',
>         'platform',
>     ],
>     // 是否通过 表名（下划线分割）来生成 ddd 目录结构， 
>     'for_table_ddd' => false,
>     // 模块，定义模块顺序
>     'modules' => [],
> ];
> ```
>
> 3，`php bin/hyperf.php gen:code --help` 查看命令参数
>
> 4，会自动生成curd 代码，同时会生成一个常量访问接口

## 目录结构

### 普通目录

| 目录名称                | 说明                                   |
| ----------------------- |--------------------------------------|
| Common                  | 骨架自带的公共类                             |
| Constants               | 静态枚举类目录，建议再创建Status,Enums,Types三个子目录 |
| Constants/Errors        | 自定义错误码目录                             |
| Controller              | 接口控制器目录                              |
| Entity                  | 请求和返回实体                              |
| Infrastructure          | 服务接口，可对外暴露提供RPC接口，实现微服务调用            |
| Model        | mysql model  和 字段实体                  |
| Service                 | 公共服务， 可对外暴露提供RPC接口，实现微服务调用           |

### DDD 目录结构

| 目录名称                    | 说明                                   |
| --------------------------- |--------------------------------------|
| Common                      | 骨架自带的公共类                             |
| Infrastructure              | 服务接口，可对外暴露提供RPC接口，实现微服务调用            |
| 领域/Application            | 接口控制器目录                              |
| 领域/Constants              | 静态枚举类目录，建议再创建Status,Enums,Types三个子目录 |
| 领域/Constants/Errors       | 自定义错误码目录                             |
| 领域/Entity                 | 请求和返回实体                              |
| 领域/Model        | mysql model   和 字段实体                             |
| 领域/Service                | 公共服务， 可对外暴露提供RPC接口，实现微服务调用           |

## 项目启动

```php
composer start
    
//  会自动启动 swagger 服务，访问http://127.0.0.1:9501/swagger， 如果不需要 可以再 config/autoload/api_docs.php 关闭
```

## 比较常用用法提示

### 1，枚举

> 在没有使用php8.1 的时候官方自带enum，先使用 `marc-mabe/php-enum` 封装的枚举类对象

```php

<?php

declare(strict_types=1);

namespace Lengbin\Hyperf\Common\Constants;

use Lengbin\ErrorCode\AbstractEnum;
use Lengbin\ErrorCode\Annotation\EnumMessage;

/**
 * 基础状态
 * @method static BaseStatus FROZEN()
 * @method static BaseStatus NORMAL()
 */
class BaseStatus extends AbstractEnum
{
    /**
     * @Message("禁用")
     */
    #[EnumMessage("禁用")]
    const FROZEN = 0;

    /**
     * @Message("正常")
     */
    #[EnumMessage("正常")]
    const NORMAL = 1;
}


// 使用
    $status = BaseStatus::NORMAL();
    var_dump($status->getValue()); // 获取数字值
    var_dump($status->getMessage()); // 获取关联信息
// 具体用法 查看 `marc-mabe/php-enum` 库
```

### 2 BaseObject

```php
/**
 * Class SystemHelpItem.
 */
class SystemHelpItem extends BaseObject
{
    #[ApiModelProperty('ID')]
    public int $id;

    #[ApiModelProperty('名称')]
    public string $name;

    #[ApiModelProperty('名称'), Integer]
    public BaseStatus $status;
}

// 使用
$item = new SystemHelpItem([
    'id' => 1,
    'name' => "demo",
    'status' => 1
]);

  var_dump($item->status->getValue()) // 1
  var_dump($item->toArray()); // ['id' => 1,'name' => "demo", 'status' => 1]

```

简要说明 暂时到这，后面有问题 提issues， 散会
