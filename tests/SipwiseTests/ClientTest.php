<?php
namespace Sipwise\Tests;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Sipwise\HttpClient\Builder;
use Http\Adapter\Guzzle6\Client;
use Psr\Http\Message\RequestInterface;
/**
 * Description of ClientTest
 *
 * @author Nova Kurniawan <novadwikurniawan@gmail.com>
 */
class ClientTest extends TestCase
{
    protected $GuzzleAdapter;
    protected $defaultBuilder;
    protected $defaultClient;
    protected $client;
    protected $builder;

    public function setUp()
    {
        parent::setUp();
    }
    
    public function tearDown()
    {
        parent::tearDown();
    }

    //test with guzzle handler
    public function testGuzzleClient()
    {
        $mock = new MockHandler([]);
        
        $handler = HandlerStack::create($mock);
        $client = new \GuzzleHttp\Client(['handler' => $handler]);
        $clientAdapter = new Client($client);
        $builder = new Builder($clientAdapter);
        
        $sipwiseClient = new \Sipwise\Client($builder);
        
        $this->assertInstanceOf(\Sipwise\Client::class, $sipwiseClient);
    }
    
    //test with default handler
    public function testDefaultHandler()
    {
        $this->defaultbuilder = $this->getMockBuilder(\Sipwise\HttpClient\Builder::class)
                                ->setConstructorArgs([new Client()])
                                ->getMock();

        $this->defaultClient = new \Sipwise\Client($this->defaultBuilder);
        
        $this->assertInstanceOf(\Sipwise\Client::class, $this->defaultClient);
    }
    
    //test can send with guzzle client
    public function testClientSend()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode(['ini test']))
        ]);
        
        $handler = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handler]);
        
        $this->assertEquals(200, $guzzleClient->get('/')->getStatusCode());
    }
    
    //test can send with default client
    public function testClientDefaultSend()
    {
        $client = new \Http\Mock\Client();
        
        $response = $this->createMock('Psr\Http\Message\ResponseInterface');
        $client->addResponse($response);
    
        $request = $this->createMock(RequestInterface::class);
            
        $returnedResponse = $client->sendRequest($request);
        $this->assertSame($response, $returnedResponse);
        $this->assertSame($request, $client->getLastRequest());
    }
    
    
    public function testApiCall()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode(['sipwise' => 'test']))
        ]);
        
        $handler = HandlerStack::create($mock);
        $guzzleClient = new \GuzzleHttp\Client(['handler' => $handler]);
        $httpClient = new Client($guzzleClient);
        
        $sipwiseClient = \Sipwise\Client::createWithHttpClient($httpClient);
        
        $this->assertJson($sipwiseClient->api('subscribers')->all(['username'=>1]));
    }
}
