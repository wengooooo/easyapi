<?php
/**
 * This file is part of the wengo/basesdk.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */


namespace EasyApi;

/**
 * Class Factory.
 *
 * @method static \EasyApi\Applications\Base\Application            Base(array $config)

 */

class Factory
{
    public static function make($name, $config) {
        $namespace = \EasyApi\Core\Support\Str::studly($name);
        $application = "\\EasyApi\\Applications\\{$namespace}\\Application";

        return new $application($config);
    }

    public static function __callStatic($name, $arguments) {
        return self::make($name, ...$arguments);
    }
}

