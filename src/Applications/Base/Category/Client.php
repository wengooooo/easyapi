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
     * @param array $categories
     * @return array
     */
    public function addCategory($category) {
        return $this->app['client']->makeRequest('/1/categories/add', http_build_query($category));
    }

    /**
     * 编辑目录
     * @param array $categories
     * @return array
     */
    public function editCategory($category) {
        return $this->app['client']->makeRequest('/1/categories/edit', http_build_query($category));
    }

    /**
     * 删除目录
     * @param array $category_ids
     * @return array
     */
    public function deleteCategory($category) {
        return $this->app['client']->makeRequest('/1/categories/delete', http_build_query($category));
    }
}
