<?php

/*
 * This file is part of the shaymo/feieyun.
 *
 * (c) shayvmo <1006897172@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
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
