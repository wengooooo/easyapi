<?php

namespace EasyApi\Core\Providers;

use EasyApi\Core\BaseClient;
use GuzzleHttp\Client;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class HttpClientServiceProvider.
 *
 */
class HttpClientServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $pimple['client'] = function ($app) {
            return new BaseClient($app);
//            return new Client($app['config']->get('http', []));
        };
    }
}
