<?php
/**
 * This file is part of the wengo/basesdk.
 *
 * (c) basesdk <398711943@qq.com>
 *
 */

namespace EasyApi\Applications\BaiduAi\ImageSearch;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface {
    public function register(Container $app) {
        $app['search'] = function($app) {
            return new Client($app);
        };
    }
}