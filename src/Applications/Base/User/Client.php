<?php
/**
 * This file is part of the wengo/basesdk.
 *
 * (c) basesdk <398711943@qq.com>
 *
 */

namespace EasyApi\Applications\Base\User;


use EasyApi\Applications\Base\BaseClient;

class Client extends BaseClient
{
    /**
     * 获取当前用户信息
     * @return array
     */
    public function getUser() {
        $this->app['client']->makeGet('/1/users/me/');
    }
}