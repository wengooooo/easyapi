<?php
namespace EasyApi\Applications\BaiduAi\ImageSearch;


use DOMElement;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use phpQuery as pq;
use EasyApi\Applications\BaiduAi\BaseClient;

class Client extends BaseClient
{
    public $jar;

    /**
     * 获取所有产品
     * @param mixed $image
     */
    public function getSimilar($image) {
        $this->app['client']->makeRequest('rest/2.0/image-classify/v1/realtime_search/similar/search', http_build_query($image));
    }
}