> 基于 php8.0 hyperf3.0 框架封装的骨架

接口 我也经历了几个版本

- get，post
- restful api接口规范
- 万能 post

当前这个骨架 就是用 万能post，  如果不喜欢 或者不习惯，通过代码生成后， 去控制把请求方式修改一下就行了， 对吧。


# 如何使用

## 管理台
请移驾到 [vue3-element-admin](https://github.com/youlaitech/vue3-element-admin)

## 前端代码修改 
```vue
// vue3-element-admin/src/enums/ResultEnum.ts
export const enum ResultEnum {
    /**
    * 成功
    */
    SUCCESS = 0,

    /**
    * 错误
    */
    ERROR = "B0001",
    
    /**
    * 令牌无效或过期
    */
    TOKEN_INVALID = 408,
}

```