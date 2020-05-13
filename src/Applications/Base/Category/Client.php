<?php
namespace EasyApi\Applications\Base\Category;


use DOMElement;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use phpQuery as pq;
use EasyApi\Applications\Base\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取所有目录
     * @return array
     */
    public function getCategories() {
        return $this->app['client']->makeRequest('/1/categories', null, [], 'get');
    }

    /**
     * 添加目录
     * @param string $name
     * @param array $options
     * @return array
     */
    public function addCategory($categories) {
        return array_map(function ($category) {
            return $this->app['client']->makeRequest('/1/categories/add', http_build_query($category));
        }, $categories);
    }

    /**
     * 编辑目录
     * @param int $category_id
     * @param array $options
     * @return array
     */
    public function editCategory($categories) {
        return array_map(function ($category) {
            return $this->app['client']->makeRequest('/1/categories/edit', http_build_query($category));
        }, $categories);
    }

    /**
     * 删除目录
     * @param int $category_id
     * @return array
     */
    public function deleteCategory($category_ids) {
        return array_map(function ($id) {
            return $this->app['client']->makeRequest('/1/categories/delete', http_build_query([
                'category_id' => $id
            ]));
        }, $category_ids);
    }
}