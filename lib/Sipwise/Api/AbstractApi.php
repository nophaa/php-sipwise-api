<?php namespace Sipwise\Api;

use Sipwise\Client;
use Sipwise\HttpClient\Message\QueryStringBuilder;
use Sipwise\HttpClient\Message\ResponseMediator;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\StreamFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Abstract class for Api classes
 *
 * @author Joseph Bielawski <stloyd@gmail.com>
 * @author Matt Humphrey <matt@m4tt.co>
 * @author Radu Topala <radu.topala@trisoft.ro>
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
abstract class AbstractApi implements ApiInterface
{
    /**
     * The client
     *
     * @var Client
     */
    protected $client;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * @param Client $client
     * @param StreamFactory|null $streamFactory
     */
    public function __construct(Client $client, StreamFactory $streamFactory = null)
    {
        $this->client = $client;
        $this->streamFactory = $streamFactory ?: StreamFactoryDiscovery::find();
    }

    /**
     * @return $this
     * @codeCoverageIgnore
     */
    public function configure()
    {
        return $this;
    }

    /**
     * Performs a GET query and returns the response as a PSR-7 response object.
     *
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @return ResponseInterface
     */
    protected function getAsResponse($path, array $parameters = array(), $requestHeaders = array())
    {
        $preparedPath = $this->preparePath($path, $parameters);
        
        return $this->client->getHttpClient()->get($preparedPath, $requestHeaders);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @return mixed
     */
    protected function get($path, array $parameters = array(), $requestHeaders = array())
    {
        return ResponseMediator::getContent($this->getAsResponse($path, $parameters, $requestHeaders));
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @param array $files
     * @return mixed
     */
    protected function post($path, array $parameters = array(), $requestHeaders = array())
    {
        $preparedPath = $this->preparePath($path);

        $body = null;
        if (!empty($parameters)) {
            $body = $this->prepareBody($parameters);
            $requestHeaders['Content-Type'] = 'application/json';
            $requestHeaders['Prefer'] = 'return=representation';
        }

        $response = $this->client->getHttpClient()->post($preparedPath, $requestHeaders, $body);
        
        if (empty(ResponseMediator::getContent($response))){
            return ResponseMediator::getHeaders($response);
        }
        return ResponseMediator::getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @return mixed
     */
    protected function put($path, array $parameters = array(), $requestHeaders = array())
    {
        $preparedPath = $this->preparePath($path);

        $body = null;
        if (!empty($parameters)) {
            $body = $this->prepareBody($parameters);
            $requestHeaders['Content-Type'] = 'application/json';
            $requestHeaders['Prefer'] = 'return=representation';
        }

        $response = $this->client->getHttpClient()->put($preparedPath, $requestHeaders, $body);

        return ResponseMediator::getContent($response);
    }
    
    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @return mixed
     */
    protected function patch($path, array $parameters = array(), $requestHeaders = array())
    {
        $preparedPath = $this->preparePath($path);

        $body = null;
        if (!empty($parameters)) {
            $body = $this->prepareBody($parameters);
            $requestHeaders['Content-Type'] = 'application/json-patch+json';
            $requestHeaders['Prefer'] = 'return=representation';
        }

        $response = $this->client->getHttpClient()->patch($preparedPath, $requestHeaders, $body);

        return ResponseMediator::getContent($response);
    }

    /**
     * @param string $path
     * @param array $parameters
     * @param array $requestHeaders
     * @return mixed
     */
    protected function delete($path, array $parameters = array(), $requestHeaders = array())
    {
        $preparedPath = $this->preparePath($path, $parameters);

        $response = $this->client->getHttpClient()->delete($preparedPath, $requestHeaders);

        return ResponseMediator::getContent($response);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function encodePath($path)
    {
        $encodedPath = rawurlencode($path);

        return str_replace('.', '%2E', $encodedPath);
    }

    /**
     * Create a new OptionsResolver with page and per_page options.
     *
     * @return OptionsResolver
     */
    protected function createOptionsResolver(array $parameters = [])
    {
        $resolver = new OptionsResolver();
        
        $resolver->setDefined(array_keys($parameters));
        
        $resolver->setDefined('page')
            ->setAllowedTypes('page', 'int')
            ->setAllowedValues('page', function ($value) {
                return $value > 0;
            });
        $resolver->setDefined('rows')
            ->setAllowedTypes('rows', 'int')
            ->setAllowedValues('rows', function ($value) {
                return $value > 0 && $value <= 100;
            });
            
        $resolver->setDefined('order_by')
            ->setAllowedTypes('order_by', 'string');
        
        $resolver->setDefined('order_by_direction')
            ->setAllowedTypes('order_by_direction', 'string');

        return $resolver;
    }
    
    protected function createEditOptionsResolver(array $parameters = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefined('op')
            ->setAllowedTypes('op', 'string')->setAllowedValues('op',['remove', 'add', 'replace', 'move', 'copy']);
        
        $resolver->setDefined('path')
            ->setAllowedTypes('path', 'string');
        
        $resolver->setDefined('value')
            ->setAllowedTypes('value', ['integer','string', 'bool']);
        
        foreach($parameters as $parameter){
            $resolver->resolve($parameter);
        }

        return $resolver;
    }

    /**
     * @param array $parameters
     * @return StreamInterface
     */
    private function prepareBody(array $parameters = [])
    {
        $raw = json_encode($parameters);
        $stream = $this->streamFactory->createStream($raw);
        return $stream;
    }

    private function preparePath($path, array $parameters = [])
    {
        if (count($parameters) > 0) {
            $path .= '?'.QueryStringBuilder::build($parameters);
        }

        return $path;
    }

    /**
     * @param $file
     *
     * @return string
     */
    private function guessContentType($file)
    {
        if (!class_exists(\finfo::class, false)) {
            return 'application/octet-stream';
        }
        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        return $finfo->file($file);
    }
}
