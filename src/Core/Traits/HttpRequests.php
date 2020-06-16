<?php

namespace EasyApi\Core\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Pool;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HttpRequests.
 *
 */
trait HttpRequests
{
    use ResponseCastable;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * 中间件
     * @var array
     */
    protected $middlewares = [];

    /**
     * 处理者堆栈
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * 缺省设置
     * @var array
     */
    protected static $defaults = [
        'curl' => [
//            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
        'response_type' => 'array',
        'http' => [
            'max_retries' => 5,
            'retry_delay' => 500,
            'timeout' => 120,
//            'debug' => true,
            'cookies' => true,
        ]
    ];

    /**
     * 设置guzzle缺省配置
     *
     * @param array $defaults
     */
    public static function setDefaultOptions($defaults = [])
    {
        self::$defaults = $defaults;
    }

    /**
     * 获取guzzle缺省设置
     *
     * @return array
     */
    public static function getDefaultOptions(): array
    {
        return self::$defaults;
    }

    /**
     * 设置 GuzzleHttp\Client.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {

        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * 获取 GuzzleHttp\ClientInterface 对象实例.
     *
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {

        if (!($this->httpClient instanceof ClientInterface)) {
            $options = array_merge(self::$defaults['http'], $this->app->config->get('http'));
            $options['curl'] = array_merge(self::$defaults['curl'], $this->app->config->get('curl', []));
            $options['handler'] = $this->getHandlerStack();

            $this->httpClient = new Client($options);
        }

        return $this->httpClient;
    }

    /**
     * 添加中间件.
     *
     * @param callable $middleware
     * @param string   $name
     *
     * @return $this
     */
    public function pushMiddleware(callable $middleware, string $name = null)
    {
        if (!is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            array_push($this->middlewares, $middleware);
        }

        return $this;
    }

    /**
     * 获取所有中间件.
     *
     * @return array
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * 获取Cookies.
     *
     * @return array
     */
    public function getCookies() {
        return $this->getHttpClient()->getConfig('cookies');
    }

    /**
     * 发起Http请求.
     *
     * @param string $url
     * @param string $method
     * @param array  $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request($url, $method = 'GET', $options = []): ResponseInterface
    {
        $method = strtoupper($method);

        $options = array_merge(self::$defaults, $options, ['handler' => $this->getHandlerStack()]);

        if (property_exists($this, 'baseUri') && !is_null($this->baseUri)) {
            $options['base_uri'] = $this->baseUri;
        }

        $response = $this->getHttpClient()->request($method, $url, $options);

        $response->getBody()->rewind();

        return $response;
    }

    /**
     * 发送请求
     * @return void
     */

    /**
     * @param \GuzzleHttp\HandlerStack $handlerStack
     *
     * @return $this
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;

        return $this;
    }

    /**
     * 创建一个handler stack. 用来装载中间件
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create($this->getGuzzleHandler());

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }


    /**
     * 获取 guzzle handler.
     *
     * @return callable
     */
    protected function getGuzzleHandler()
    {
        if (property_exists($this, 'app') && isset($this->app['guzzle_handler'])) {
            return is_string($handler = $this->app->raw('guzzle_handler'))
                        ? new $handler()
                        : $handler;
        }

        return \GuzzleHttp\choose_handler();
    }
}
