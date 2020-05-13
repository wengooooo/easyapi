<?php
namespace EasyApi\Applications\Base\DeliveryCompany;


use DOMElement;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use phpQuery as pq;
use EasyApi\Applications\Base\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取所有订单
     * @ref https://docs.thebase.in/docs/api/orders/
     * @param  array $orderDate[start_date, end_date]
     * @param  array $options
     */
    public function getDeliveryCompanies() {
        return $this->app['client']->makeRequest('/1/delivery_companies', null, [], 'get');
    }
}