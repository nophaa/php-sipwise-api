<?php

namespace Sipwise;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;
use Psr\Cache\CacheItemPoolInterface;
use Sipwise\Api\ApiInterface;
use Sipwise\Exception\BadMethodCallException;
use Sipwise\Exception\InvalidArgumentException;
use Sipwise\HttpClient\Builder;
use Sipwise\HttpClient\Plugin\Authentication;
use Sipwise\HttpClient\Plugin\History;
use Sipwise\HttpClient\Plugin\SipwiseExceptionThrower;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Simple yet very cool PHP Sipwise client.
 *
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class Client
{
    /**
     * Constant for authentication method. Indicates the new favored login method
     * with username and password via HTTP Authentication.
     */
    const AUTH_HTTP_PASSWORD = 'http_password';

    /**
     * @var string
     */
    private $apiVersion;

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var History
     */
    private $responseHistory;

    /**
     * @var Options Resolver for sipwise default config
     */
    private $options;

    /**
     * Instantiate a new Sipwise client.
     *
     * @param Builder|null $httpClientBuilder
     * @param string|null  $apiVersion
     * @param string|null  $enterpriseUrl
     */
    public function __construct(Builder $httpClientBuilder = null, array $config = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($config);

        $this->responseHistory = new History();
        $this->httpClientBuilder = $builder = $httpClientBuilder ?: new Builder();

        $builder->addPlugin(new SipwiseExceptionThrower());
        $builder->addPlugin(new Plugin\HistoryPlugin($this->responseHistory));
        $builder->addPlugin(new Plugin\RedirectPlugin());
        $builder->addPlugin(new Plugin\HeaderDefaultsPlugin([
            'User-Agent' => 'php-sipwise-api',
        ]));
        $builder->addHeaderValue('Accept', 'application/json');
        $builder->addHeaderValue('Prefer', $this->options['return']);
    }

    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'schema' => 'https',
            'return' => 'return=representation',
            'port'   => 1443,
        ]);
        $resolver->addAllowedValues('schema', ['http', 'https']);
    }

    /**
     * Create a Sipwise\Client using a HttpClient.
     *
     * @param HttpClient $httpClient
     *
     * @return Client
     */
    public static function createWithHttpClient(HttpClient $httpClient)
    {
        $builder = new Builder($httpClient);

        return new self($builder);
    }

    /**
     * @param string $name
     *
     * @throws InvalidArgumentException
     *
     * @return ApiInterface
     */
    public function api($name)
    {
        switch ($name) {

            case 'customer':
            case 'customers':
                $api = new Api\Customers($this);
                break;
            case 'subscriber':
            case 'subscribers':
                $api = new Api\Subscribers($this);
                break;
            case 'subscriberPreferences':
                $api = new Api\Subscribers($this);
                break;
            case 'calls':
                $api = new Api\Calls($this);
                break;
            case 'voicemail':
                $api = new Api\VoiceMails($this);
                break;
            default:
                throw new InvalidArgumentException(sprintf('Undefined api instance called: "%s"', $name));
        }

        return $api;
    }

    /**
     * Authenticate a user for all next requests.
     *
     * @param string      $tokenOrLogin Sipwise private token/username/client ID
     * @param null|string $password     Sipwise password/secret (optionally can contain $authMethod)
     * @param null|string $authMethod   One of the AUTH_* class constants
     *
     * @throws InvalidArgumentException If no authentication method was given
     */
    public function authenticate($tokenOrLogin, $password = null, $authMethod = null)
    {
        if (null === $password && null === $authMethod) {
            throw new InvalidArgumentException('You need to specify authentication method!');
        }

        if (null === $authMethod && in_array($password, [self::AUTH_HTTP_PASSWORD])) {
            $authMethod = $password;
            $password = null;
        }

        if (null === $authMethod) {
            $authMethod = self::AUTH_HTTP_PASSWORD;
        }

        $this->getHttpClientBuilder()->removePlugin(Authentication::class);
        $this->getHttpClientBuilder()->addPlugin(new Authentication($tokenOrLogin, $password, $authMethod));
    }

    public function setUrl($url)
    {
        $this->getHttpClientBuilder()->removePlugin(AddHostPlugin::class);
        $this->getHttpClientBuilder()->addPlugin(new Plugin\AddHostPlugin(UriFactoryDiscovery::find()->createUri($url)));
    }

    /**
     * Add a cache plugin to cache responses locally.
     *
     * @param CacheItemPoolInterface $cachePool
     * @param array                  $config
     */
    public function addCache(CacheItemPoolInterface $cachePool, array $config = [])
    {
        $this->getHttpClientBuilder()->addCache($cachePool, $config);
    }

    /**
     * Remove the cache plugin.
     */
    public function removeCache()
    {
        $this->getHttpClientBuilder()->removeCache();
    }

    /**
     * @param string $name
     *
     * @throws BadMethodCallException
     *
     * @return ApiInterface
     */
    public function __call($name, $args)
    {
        try {
            return $this->api($name);
        } catch (InvalidArgumentException $e) {
            throw new BadMethodCallException(sprintf('Undefined method called: "%s"', $name));
        }
    }

    /**
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function getLastResponse()
    {
        return $this->responseHistory->getLastResponse();
    }

    /**
     * @return HttpMethodsClient
     */
    public function getHttpClient()
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    /**
     * @return Builder
     */
    protected function getHttpClientBuilder()
    {
        return $this->httpClientBuilder;
    }
}
