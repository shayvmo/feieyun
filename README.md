<h1 align="center"> 飞鹅打印机接口 </h1>

<p align="center"> 飞鹅打印机</p>

[![Build Status](https://travis-ci.com/shayvmo/feieyun.svg?branch=main)](https://travis-ci.com/shayvmo/feieyun)
[![StyleCI](https://github.styleci.io/repos/373069835/shield?branch=main)](https://github.styleci.io/repos/373069835?branch=main)

## FeiEYun

---

基于 [飞鹅云开放平台](http://help.feieyun.com/document.php) 的 PHP 接口组件


github地址：[https://github.com/shayvmo/feieyun](https://github.com/shayvmo/feieyun)

码云地址：[https://gitee.com/shayvmo/feieyun](https://gitee.com/shayvmo/feieyun)

## 安装

```shell
$ composer require shayvmo/feieyun -vvv
```

## 配置

在使用本拓展之前，请先注册 [飞鹅云开放平台](http://help.feieyun.com/document.php) 账号，获取到相应的用户名和 api key

## 使用

```php
$username = 'username';
$u_key = 'u_key';

$feieyun = new \Shayvmo\Feieyun\FeiEYun($username, $u_key);

// 各个接口定义私有参数
$private_params = ['sn' => '打印机编号'];

// 方式1,设置公共参数apiname, eg: Open_queryPrinterStatus
$response_data = $feieyun->setApiName('Open_queryPrinterStatus')->request($private_params);

// 方式2,使用已定义的接口类
$response_data = $feieyun->checkPrinterStatus($private_params);
```

### Laravel

支持 laravel 5.5 以上

#### 在 Laravel 使用

`config/services.php` 中配置以下

```
'feieyun' => [
    'username' => '',
    'ukey' => '',
],
```

方法参数注入

```php
use Shayvmo\Feieyun\FeiEYun;

class FeiEYunController extends Controller
{
    public function show(FeiEYun $feiEYun)
    {
        return response()->json($feiEYun->checkPrinterStatus(['sn'=>'xxx']));
    }
}
```

服务名访问

```php
class FeiEYunController extends Controller
{
    public function show()
    {
        return response()->json(app('feieyun')->checkPrinterStatus(['sn'=>'xxx']));
    }
}
```

### 查询打印机状态

```php
$response = $feieyun->checkPrinterStatus(['sn'=>'xxxxx']);
```

示例：
```json
{
    "msg":"ok",
    "ret":0,
    "data":"离线。",
    "serverExecutedTime":3
}
```

### 已定义的接口类

添加打印机: `addPrinter`

小票机打印订单: `createPrintOrder`

标签机打印订单: `createPrintLabelOrder`

删除打印机: `delPrinter`

修改打印机信息: `modifyPrinter`

清空待打印订单: `clearPrinterSqs`

查询订单状态: `queryOrderState`

查询打印机订单数: `queryOrderInfoByDate`

查询打印机状态: `checkPrinterStatus`

## 参考

[飞鹅云开放平台](http://help.feieyun.com/document.php)

## 贡献代码

欢迎各位一起讨论


## License

MIT