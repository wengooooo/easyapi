<?php
namespace EasyApi\Applications\Base\ItemCategories;


use DOMElement;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use phpQuery as pq;
use EasyApi\Applications\Base\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取目录产品对应关系
     * @param  int $item_id
     * @return array
     */
    public function getItemToCagegories($item_ids) {
        return array_map(function ($item_id) {
            return $this->app['client']->makeRequest(sprintf('/1/item_categories/detail/%s', $item_id), null, [], 'get');
        }, $item_ids);
    }

    /**
     * 添加目录产品对应关系
     * @param  int $item_id
     * @param  array $options
     * @return array
     */
    public function addItemToCagegories($item_categories) {
        return array_map(function ($item_category) {
            return $this->app['client']->makeRequest('/1/item_categories/add', http_build_query($item_category));
        }, $item_categories);
    }

    /**
     * 删除目录产品对应关系
     * @param  int $item_category_id
     * @return array
     */
    public function deleteItemToCagegories($item_category_ids) {
        return array_map(function ($id) {
            return $this->app['client']->makeRequest('/1/item_categories/delete', http_build_query([
                'item_category_id'  => $id
            ]));
        }, $item_category_ids);
    }
}