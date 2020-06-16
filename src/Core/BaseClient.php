<?php

namespace EasyApi\Core;


use EasyApi\Core\Traits\WithLock;
use Guzzle\Http\Exception\RequestException;
use GuzzleHttp\Pool;
use EasyApi\Core\Traits\HttpRequests;
use GuzzleHttp\Psr7\Request;

class BaseClient
{
    use HttpRequests { request as performRequest;}
    use WithLock;

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

    public function addRequest($requests) {
        $requests = is_array($requests) ? $requests : [$requests];
        array_map(function($request) {
            if($request instanceof \GuzzleHttp\Psr7\Request) {
                $this->requests[] = $request;
            } else {
                throw new RequestException();
            }

        }, $requests);
    }

    public function getRequests() {
        return $this->requests;
    }

    public function execute() {

        $responseList =  Pool::batch($this->getHttpClient(), $this->requests, ['concurrency' => 10]);
//        $responseList =  Pool::batch($this->getHttpClient(), $this->requests, ['options' => ['handler' => $this->getHandlerStack()]]);
        foreach($responseList as &$response) {
            $response = $this->castResponseToType($response, $this->app->config->get('response_type'));
        }

        $this->resetRequests();
        return $responseList;
    }

    public function asyncExecute($fulfilled, $rejected) {
        $pool = new Pool($this->getHttpClient(), $this->requests, [
            'concurrency' => 10,
            'fulfilled' => $fulfilled,
            'rejected' => $rejected,
        ]);

        $promise = $pool->promise();
        $promise->wait();
        $this->resetRequests();
    }

    public function resetRequests() {
        $this->requests = [];
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

        return $request;
    }
}
