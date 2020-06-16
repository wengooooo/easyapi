<?php
/**
 * This file is part of the wengo/basesdk.
 *
 * (c) basesdk <398711943@qq.com>
 *
 */

namespace EasyApi\Applications\Base;

use EasyApi\Core\ServiceContainer;

/**
 * Class Application.
 *
* @property \EasyApi\Applications\Base\Order\Client                               $order
* @property \EasyApi\Applications\Base\Items\Client                               $items
* @property \EasyApi\Applications\Base\Category\Client                            $category
* @property \EasyApi\Applications\Base\DeliveryCompany\Client                     $delivery_company
* @property \EasyApi\Applications\Base\ItemCategories\Client                      $item_categories
* @property \EasyApi\Applications\Base\User\Client                                $user
* @property \EasyApi\Applications\Base\OAuth\Provider\BaseProvider                $oauth
*/
class Application extends ServiceContainer {
    protected $providers = [
        Category\ServiceProvider::class,
        DeliveryCompany\ServiceProvider::class,
        ItemCategories\ServiceProvider::class,
//        Savings\ServiceProvider::class,
        User\ServiceProvider::class,
        Items\ServiceProvider::class,
        Order\ServiceProvider::class
    ];
}