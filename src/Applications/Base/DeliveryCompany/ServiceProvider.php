<?php

namespace EasyApi\Applications\Base\DeliveryCompany;


use Pimple\Container;
use Pimple\ServiceProviderInterface;


class ServiceProvider implements ServiceProviderInterface {
    public function register(Container $app) {
        $app['delivery'] = function($app) {
            return new Client($app);
        };
    }
}