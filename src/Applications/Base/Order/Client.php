<?php
namespace EasyApi\Applications\Base\Order;


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
     * @return array
     */
    public function getOrders($orders): array {
        return array_map(function ($order) {
            $allowParams = ['start_ordered', 'end_ordered', 'limit', 'offset'];
            return $this->app['client']->makeRequest(sprintf('/1/orders?%s', http_build_query($order)), null, [], 'get');
        }, $orders);

    }

    /**
     * 获取订单详情
     * @param  string $uniqueKey
     * @return array
     */
    public function getOrderDetail($orders): array {
        return array_map(function ($order) {
            $allowParams = ['start_ordered', 'end_ordered', 'limit', 'offset'];
            return $this->app['client']->makeRequest(sprintf('/1/orders/detail/%s', $order['id']), null, [], 'get');
        }, $orders);

    }

    /**
     * 更新订单产品信息
     * @param  int $order_item_id
     * @param  string $status
     * @param  string $add_comment
     * @param  string $atobarai_status
     * @param  int $delivery_company_id
     * @param  string $tracking_number
     * @return array
     */
    public function updateOrder($orders): array {
        return array_map(function ($order) {
            $allowParams = ['start_ordered', 'end_ordered', 'limit', 'offset'];
            return $this->app['client']->makeRequest('/1/orders/edit_status', http_build_query([
                'order_item_id' => $order['order_item_id'],
                'status' => $order['status'],
                'add_comment' => mb_strlen($order['add_comment']) > 250 ? mb_substr($order['add_comment'], 0, 250) : $order['add_comment'],
                'atobarai_status' => $order['atobarai_status'],
                'delivery_company_id' => $order['delivery_company_id'],
                'tracking_number' => $order['tracking_number'],
            ]));
        }, $orders);

    }
}