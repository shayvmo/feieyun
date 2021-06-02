<?php

/*
 * This file is part of the shaymo/feieyun.
 *
 * (c) shayvmo <1006897172@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Shayvmo\Feieyun\Apis;

class ClearPrinterSqs implements ApiInterface
{
    public function getApiName(): string
    {
        return 'Open_delPrinterSqs';
    }
}
