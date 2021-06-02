<?php
/**
 * FeiEYun.
 *
 * @ClassName ServiceProvider
 * @Author Administrator
 * @Date 2021-06-02 15:09 星期三
 * @Version 1.0
 * @Description
 */

namespace Shayvmo\Feieyun;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(FeiEYun::class, function () {
            return new FeiEYun(config('services.feieyun.username'), config('services.feieyun.ukey'));
        });

        $this->app->alias(FeiEYun::class, 'feieyun');
    }

    public function provides()
    {
        return [FeiEYun::class, 'feieyun'];
    }
}
