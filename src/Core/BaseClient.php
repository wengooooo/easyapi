<?php

namespace EasyApi\Core;


use EasyApi\Core\Exceptions\RuntimeException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;

use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use EasyApi\Core\Contracts\AccessTokenInterface;
use EasyApi\Core\Traits\HttpRequests;

class BaseClient
{
    use HttpRequests { request as performRequest;}

    /**
     * @var \EasyApi\Core\ServiceContainer
     */
    protected $app;

    protected $client;

    public $headers = ['user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36'];


    protected $requests = [];
    /**
     * BaseClient constructor.
     *
     * @param \EasyApi\Core\ServiceContainer                    $app
     * @param \EasyApi\Core\Contracts\AccessTokenInterface|null $accessToken
     */
    
    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    public function makePost(string $url, array $data = [], array $query = [])
    {
        $options = ['form_params' => $data];
        if ($query) {
            $options['query'] = $query;
        }

        $this->addRequest(new Request('POST', $url));
//        return $this->request($url, 'POST', $options);
    }

    public function makeGet(string $url, array $query = []) {
        $this->addRequest(new Request('GET', $url));
//        return $this->request($url, 'GET', ['query' => $query]);
    }

    /**
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool   $returnRaw
     *
     * @return \Psr\Http\Message\ResponseInterface|\EasyApi\Core\Support\Collection|array|object|string
     *
     * @throws \EasyApi\Core\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $url, string $method = 'GET', array $options = [], $returnRaw = false)
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        $response = $this->performRequest($url, $method, $options);

        return $returnRaw ? $response : $this->castResponseToType($response, $this->app->config->get('response_type'));
    }

    public function addRequest(Request $request) {
        $this->requests[] = $request;
    }

    public function execute() {
        $this->app->oauth->getToken(true);

        $responseList =  Pool::batch($this->getHttpClient(), $this->requests, ['options' => ['handler' => $this->getHandlerStack()]]);
        foreach($responseList as &$response) {
            $response = $this->castResponseToType($response, $this->app->config->get('response_type'));
        }

        return $responseList;
    }

    /**
     * 注册 Guzzle 中间件.
     */
    protected function registerHttpMiddlewares()
    {

    }

    /**
     * 过滤请求无效的参数
     * @desc 只能过滤一维的无效参数
     *
     * @param array $allow
     * @param array $options
     * @return \Closure
     */
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

    public function getHeaders() {
        return $this->headers;
    }

    public function makeRequest($endpoint, $body, $headers = [], $method = 'post') {
        $headers = array_merge($this->getHeaders(), $headers);
        $method = strtolower($method);

        if($method == 'post') {
            switch (gettype($body)) {
                case 'resource':
                    $body = new \GuzzleHttp\Psr7\MultipartStream([['name' => 'file','contents' => $body]]);
                    $headers['Content-Type'] = 'multipart/form-data; boundary=' . $body->getBoundary();
                    break;
                default:
                    $headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    break;
            }

        } else {
            $headers['Content-Type'] = 'text/html';
        }


        $request = new Request($method, $endpoint, $headers, $body);
        $this->addRequest($request);
        return $request;
    }
}