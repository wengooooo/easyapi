<?php
require_once('vendor/autoload.php');
use Symfony\Component\Cache\Adapter\RedisAdapter;

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$cache = new RedisAdapter($redis);

$config = [
    'client_id' => 'de650b8436de521e43239a63968646b5',
    'client_secret' => 'e2bc32a7969920976409b0baf7e8888b',
    'response_type' => 'array',
    'oauth' => [
        'redirect_uri' => 'http://39.98.211.57/accesssites/auth.php',
//        'username' => 'sale@1999pop.com',
//        'password' => '1q1q1q1q',
//        'shop_name' => '1999pop',

        'username' => '1403676738@qq.com',
        'password' => '1q1q1q1q',
        'shop_name' => 'easydresser',
    ],

    'http' => [
        'max_retries' => 3,
        'retry_delay' => 500,
        'timeout' => 120,
        'debug' => true,
        'base_uri' => 'https://api.thebase.in/',
        'cookies' => true,
    ],
    'mulit_http' => [
        'concurrency' => 6,
    ],
    'cache' => $cache,
    'redis' => [
        'scheme' => 'tcp',
        'host' =>  '127.0.0.1',
        'port' => 6379,
    ]
];

$app = EasyApi\Factory::Base($config);
//$app->oauth->initializeToken();

$app->user->getUser();
$app->user->getUser();
//$app->items->getItems();
//$app->items->getItems();

dd($app->client->execute());

dd($app);