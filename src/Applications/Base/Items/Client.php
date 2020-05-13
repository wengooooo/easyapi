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
     * @param  array $items
     */
    public function getItems($items, $limit = 100) {
        return array_map(function ($item) use ($limit) {
            $item['limit'] = $limit;
            return $this->app['client']->makeRequest(sprintf('/1/items?%s', http_build_query($item)), null, [], 'get');
        }, $items);
    }

    /**
     * 搜索产品
     * @param  string $keyword
     * @param  array $options
     * @return array
     */
    public function search($keywords, ...$options) {
        return array_map(function ($keyword) use ($options) {
            $keyword = ['q' => $keyword];
            return $this->app['client']->makeRequest(sprintf('/1/items/search?%s', http_build_query($keyword)), null, [], 'get');
        }, $keywords);
    }

    /**
     * 获取产品信息
     * @param int $item_id
     * @return array
     */
    public function getItem($item_ids) {
        return array_map(function ($item_id) {
            return $this->app['client']->makeRequest(sprintf('/1/items/detail/%s', $item_id), null, [], 'get');
        }, $item_ids);
    }

    /**
     * 添加产品
     * @param string $title
     * @param string $price
     * @param string $stock
     * @param array $options
     * @return array
     */
    public function addItem($items) {
        $allowParams = ['title', 'detail', 'price', 'stock', 'visible', 'item_tax_type', 'identifier', 'list_order', 'variation', 'variation_stock', 'variation_identifier'];

        return array_map(function ($item) {
            return $this->app['client']->makeRequest('/1/items/add', http_build_query($item));
        }, $items);

    }

    /**
     * 编辑产品
     * @param int $item_id
     * @param array $options
     * @return array
     */
    public function editItem($items) {
//        $allowParams = ['item_id', 'title', 'detail', 'price', 'stock', 'visible', 'item_tax_type', 'identifier', 'list_order', 'variation_id', 'variation', 'variation_stock', 'variation_identifier'];
//        $options['item_id'] = $item_id;
//        $options = array_intersect_key($options, array_flip($allowParams));

        return array_map(function ($item) {
            return $this->app['client']->makeRequest('/1/items/edit', http_build_query($item));
        }, $items);
    }

    /**
     * 删除产品
     * @param int $item_id
     * @return array
     */
    public function deleteItem($item_ids) {
        return array_map(function ($item_id) {
            $item['item_id'] = $item_id;
            return $this->app['client']->makeRequest('/1/items/delete', http_build_query($item));
        }, $item_ids);
    }

    /**
     * 删除产品
     * @param int $item_id
     * @param string $image_no
     * @param string $image_url
     * @return array
     */
    public function addImage($items) {
        $requests = [];
        foreach($items as $item) {
            foreach($item as $im) {
                $requests[] = $this->app['client']->makeRequest('/1/items/add_image', http_build_query([
                    'item_id'  => $im['item_id'],
                    'image_no'  => $im['image_no'],
                    'image_url'  => $im['image_url']
                ]));
            }
        }

        return $requests;
    }

    /**
     * 删除产品
     * @param int $item_id
     * @param string $image_no
     * @return array
     */
    public function deleteImage($items) {
        $requests = [];
        foreach($items as $item) {
            foreach($item as $im) {
                $requests[] = $this->app['client']->makeRequest('/1/items/delete_image', http_build_query([
                    'item_id'  => $im['item_id'],
                    'image_no'  => $im['image_no']
                ]));
            }
        }

        return $requests;
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
    public function deleteVariation($items) {
        return array_map(function($item) {
            $item = array(
                'item_id'  => $item['item_id'],
                'variation_id'  => $item['variation_id'],
            );
            return $this->app['client']->makeRequest('/1/items/delete_variation', http_build_query($item));
        }, $items);
    }
}