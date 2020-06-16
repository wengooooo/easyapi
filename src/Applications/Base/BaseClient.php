<?php

namespace EasyApi\Applications\Base;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
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

    protected $retryCallback;

    /**
     * BaseClient constructor.
     *
     * @param \EasyApi\Core\ServiceContainer                    $app
     */

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
        $this->registerHttpMiddlewares();
    }

    /**
     * 注册 Guzzle 中间件.
     */
    protected function registerHttpMiddlewares()
    {
        $this->app['client']->pushMiddleware($this->retryMiddleware(), 'retry');
        $this->app['client']->pushMiddleware($this->accessTokenMiddleware(), 'access_token');
    }

    protected function getRetryCallback() {
        if(!$this->retryCallback) {
            $this->retryCallback = function ($retries, RequestInterface $request, ResponseInterface $response = null, $exception = null) {
                //处理服务器返回500
//            if($exception == null && $response->getStatusCode() == 500) {
//                return true;
//            }
                if ($exception instanceof ConnectException || $exception instanceof RequestException) {
                    return true;
                }

                return false;
            };
        }

        return $this->retryCallback;
    }

    protected function setRetryCallBack($callback) {
        $this->retryCallback = $callback;
    }

    public function filterOptions($allow, ...$options) {
        $mergeOptions = [];
        $this->mergeOptions($mergeOptions, $options);
        return array_intersect_key($mergeOptions, array_flip($allow));
    }

    function mergeOptions(array &$array1, $options) {
        if (is_array($options)) {
            foreach($options as $key => $value) {
                if(is_array($value)) {
                    $this->mergeOptions($array1, $value);
                } else {
                    $array1[$key] = $value;
                }
            }
        }
    }

    /**
     * Return retry middleware.
     *
     * @return \Closure
     */
    protected function retryMiddleware()
    {
        return Middleware::retry(function ($retries, RequestInterface $request, ResponseInterface $response = null, $exception = null) {
            //处理服务器返回500
//            if($exception == null && $response->getStatusCode() == 500) {
//                return true;
//            }

            if ($exception instanceof ConnectException || $exception instanceof RequestException) {
                while(true) {
                    if($this->app->config->get('access_token')()) {
                        break;
                    }

                    sleep(10);
                }

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

                if (stripos($this->app->config->get('http.base_uri'), $request->getUri()->getHost()) !== false) {
                    $request = $request->withHeader('Authorization', 'Bearer ' . $this->app->config->get('access_token')());
                }

                return $handler($request, $options);
            };
        };
    }
}
