<?php
/**
 * This file is part of the wengo/basesdk.
 *
 * (c) basesdk <398711943@qq.com>
 *
 */

namespace EasyApi\Applications\BaiduAi;

use EasyApi\Core\ServiceContainer;

/**
 * Class Application.
 *
* @property \EasyApi\Applications\BaiduAi\OAuth\Provider\BaseProvider                $oauth
* @property \EasyApi\Applications\BaiduAi\ImageSearch\Client                         $search
*/
class Application extends ServiceContainer {
    protected $providers = [
        OAuth\ServiceProvider::class,
        ImageSearch\ServiceProvider::class,
//        Category\ServiceProvider::class,
//        DeliveryCompany\ServiceProvider::class,
//        ItemCategories\ServiceProvider::class,
//        Savings\ServiceProvider::class,

////        Mulit\Items\ServiceProvider::class,
//        Order\ServiceProvider::class
    ];
}