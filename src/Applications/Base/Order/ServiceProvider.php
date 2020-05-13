<?php

namespace EasyApi\Applications\Base\Order;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface {
    public function register(Container $app) {
        $app['order'] = function($app) {
            return new Client($app);
        };
    }
}