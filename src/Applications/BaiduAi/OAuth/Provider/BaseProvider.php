<?php
/**
 * This file is part of the wengo/basesdk.
 *
 * (c) basesdk <398711943@qq.com>
 *
 */

namespace EasyApi\Applications\BaiduAi\OAuth\Provider;


use DOMElement;
use GuzzleHttp\Psr7\Uri;
use League\OAuth2\Client\Provider\GenericProvider;
use EasyApi\Core\ServiceContainer;
use GuzzleHttp\Client;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use EasyApi\Core\Exceptions\RuntimeException;
use EasyApi\Core\Traits\HttpRequests;
use EasyApi\Core\Traits\InteractsWithCache;

class BaseProvider extends GenericProvider
{
    /**
     * 公用缓存类
     * @var \EasyApi\Core\Traits\InteractsWithCache
     */
    use InteractsWithCache;

    /**
     * 公用Http请求类
     * @var \EasyApi\Core\Traits\InteractsWithCache
     */
    use HttpRequests;
    use BearerAuthorizationTrait;

    protected $scope = '';

    protected $state = '';

    /**
     * 容器集合
     * @var \EasyApi\Core\ServiceContainer
     */
    protected $app;

    /**
     * 请求方式
     * @var string
     */
    protected $requestMethod = 'POST';

    /**
     * @var array
     */
    protected $token;

    /**
     * @var int
     */
    protected $safeSeconds = 500;

    /**
     * 令牌名称
     * @var string
     */
    protected $tokenKey = 'access_token';

    /**
     * 刷新令牌名称
     * @var string
     */
    protected $refreshKey = 'refresh_token';

    /**
     * 缓存前缀
     * @var string
     */
    protected $cachePrefix = 'easyapi.kernel.access_token.';

    /**
     * The base provider constructor.
     *
     * @param \EasyApi\Core\ServiceContainer $app
     * @param array $options
     * @param array $collaborators
     */
    public function __construct(ServiceContainer $app, array $options = [], array $collaborators = []) {
        $this->app = $app;
        parent::__construct($options, ['httpClient' => new Client($options)]);
    }

    /**
     * 获取The base 请求访问令牌的URL
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params) {
        return 'https://aip.baidubce.com/oauth/2.0/token';
    }



    /**
     * 检查授权是否正确
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data) {
        if (isset($data['error'])) {
            throw new IdentityProviderException(
                $data['error'] ?: $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response->getBody()
            );
        }
    }

    /**
     * 从cache中获取Token
     * @param bool $refresh
     * @return \League\OAuth2\Client\Token\AccessToken
     *
     * @throws \EasyApi\Core\Exceptions\HttpException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \EasyApi\Core\Exceptions\InvalidArgumentException
     * @throws \EasyApi\Core\Exceptions\RuntimeException
     */
    public function getToken($refresh=false, $return = false) {
        $cacheKey = $this->getCacheKey();
        $cache = $this->getCache();

        if($cache->has($cacheKey)) {
            return $cache->get($cacheKey);
        } else {
            $token = $this->getAccessToken('client_credentials');
            $this->setToken($token);
            return $token;
        }

    }

    public function applyToUri(RequestInterface $request, array $requestOptions = []): RequestInterface {
        $accessToken = $this->getToken();
        $uri = Uri::withQueryValue($request->getUri(), 'access_token', $accessToken->getToken());
        return $request->withUri($uri);
    }

    /**
     * 存储AccessToken
     * @param \League\OAuth2\Client\Token\AccessToken
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \EasyApi\Core\Exceptions\RuntimeException
     */
    public function setToken(\League\OAuth2\Client\Token\AccessToken $token) {

        $this->getCache()->set($this->getCacheKey(), $token, $token->getExpires() - $this->safeSeconds);

        if (!$this->getCache()->has($this->getCacheKey())) {
            throw new RuntimeException('Failed to cache access token.');
        }

        return $this;
    }

    /**
     * 获取缓存名字
     * @return string
     */
    protected function getCacheKey() {
        return $this->cachePrefix.'baiduai';
    }

}