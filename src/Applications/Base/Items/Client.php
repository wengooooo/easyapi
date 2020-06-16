<?php
namespace EasyApi\Applications\Base\Items;


use DOMElement;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use phpQuery as pq;
use EasyApi\Applications\Base\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取所有产品

     */
    public function getItems($item) {
        return $this->app['client']->makeRequest(sprintf('/1/items?%s', http_build_query($item)), null, [], 'get');
    }

    /**
     * 搜索产品
     * @param  string $keyword
     * @param  array $options
     * @return array
     */
    public function search($keywords) {
        return $this->app['client']->makeRequest(sprintf('/1/items/search?%s', http_build_query($keywords)), null, [], 'get');
    }

    /**
     * 获取产品信息
     * @param int $item_id
     * @return array
     */
    public function getItem($id) {
        return $this->app['client']->makeRequest(sprintf('/1/items/detail/%s', $id), null, [], 'get');
    }

    /**
     * 添加产品
     * @param string $title
     * @param string $price
     * @param string $stock
     * @param array $options
     * @return array
     */
    public function addItem($item) {
        return $this->app['client']->makeRequest('/1/items/add', http_build_query($item));
    }

    /**
     * 编辑产品
     * @param int $item_id
     * @param array $options
     * @return array
     */
    public function editItem($item) {
        return $this->app['client']->makeRequest('/1/items/edit', http_build_query($item));
    }

    /**
     * 删除产品
     * @param int $item_id
     * @return array
     */
    public function deleteItem($item) {
        return $this->app['client']->makeRequest('/1/items/delete', http_build_query($item));
    }

    /**
     * 删除产品
     * @param int $item_id
     * @param string $image_no
     * @param string $image_url
     * @return array
     */
    public function addImage($item) {
        return $this->app['client']->makeRequest('/1/items/add_image', http_build_query($item));

    }

    /**
     * 删除产品
     * @param int $item_id
     * @param string $image_no
     * @return array
     */
    public function deleteImage($item) {
        return $this->app['client']->makeRequest('/1/items/delete_image', http_build_query($item));
    }

    /**
     * 编辑库存
     * @param int $item_id
     * @param int $stock
     * @param int $variation_id
     * @param int $variation_stock
     * @return array
     */
    public function editStock($items) {
        return array_map(function($item) {
            $item = array(
                'item_id'  => $item['item_id'],
                'stock'  => $item['stock'],
                'variation_id'  => $item['variation_id'],
                'variation_stock'  => $item['variation_stock'],
            );
            return $this->app['client']->makeRequest('/1/items/edit_stock', http_build_query($item));
        }, $items);
    }

    /**
     * 删除变体
     * @param int $item_id
     * @param int $variation_id
     * @return array
     */
    public function deleteVariation($item) {
        return $this->app['client']->makeRequest('/1/items/delete_variation', http_build_query($item));
    }
}
