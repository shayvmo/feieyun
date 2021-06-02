<?php
/**
 * FeiEYun
 *
 * @ClassName CheckPrinterStatus
 * @Author shayvmo
 * @Date 2021-06-01 22:33 星期二
 * @Version 1.0
 * @Description
 */


namespace Shayvmo\Feieyun\Apis;


class CheckPrinterStatus implements ApiInterface
{
    public function getApiName(): string
    {
        return 'Open_queryPrinterStatus';
    }
}