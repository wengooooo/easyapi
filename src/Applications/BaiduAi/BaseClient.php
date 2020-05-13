<?php

namespace EasyApi\Applications\BaiduAi;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use EasyApi\Core\Contracts\AccessTokenInterface;
use EasyApi\Core\Traits\HttpRequests;
use EasyApi\Core\ServiceContainer;

class BaseClient
{
    /**
     * @var \EasyApi\Applications\Base\OAuth\Provider\BaseProvider
     */
    protected $token;

    protected $app;
    /**
     * BaseClient constructor.
     *
     * @param \EasyApi\Core\ServiceContainer                    $app
     */

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->registerHttpMiddlewares();
        $this->token = $this->app['oauth'];
    }

    /**
     * 注册 Guzzle 中间件.
     */
    protected function registerHttpMiddlewares()
    {
        $this->app['client']->pushMiddleware($this->retryMiddleware(), 'retry');
        $this->app['client']->pushMiddleware($this->accessTokenMiddleware(), 'access_token');
    }
    
    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null,
            $exception = null
        ) {
            if ($exception instanceof ConnectException || $exception instanceof RequestException) {
                return true;
            }

            return false;
        }, function () {
            return abs($this->app->config->get('http.retry_delay', 500));
        });
    }

    /**
     * 在header添加access token.
     *
     * @return \Closure
     */
    protected function accessTokenMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {

                if ($this->token && stripos($this->app->config->get('http.base_uri'), $request->getUri()->getHost()) !== false) {
                    $request = $this->token->applyToUri($request, $options);
                }

                return $handler($request, $options);
            };
        };
    }
}