<?php

namespace EasyApi\Core\Traits;


trait WithLock
{
    public function lock() {
        return 'redis->set';
    }

    public static function releaseLock()
    {
        return <<<'LUA'
if redis.call("get",KEYS[1]) == ARGV[1] then
    return redis.call("del",KEYS[1])
else
    return 0
end
LUA;
    }
}
