<?php
namespace Sipwise\Tests;


use PHPUnit\Framework\TestCase;
use Sipwise\Api\Subscribers;

/**
 * Description of SubscribersTest
 *
 * @author myrepubliclabs
 */
class SubscribersTest extends TestCase
{
    protected $subscribers;
    public function setUp()
    {
        $this->subscribers = $this->createMock(Subscribers::class);
    }
    
    public function tearDown()
    {
        unset($this->subscribers);
    }
    
    public function testAllSubscriber()
    {
        $this->subscribers->expects($this->any())->method('all')->willReturn(json_encode(['subscriber'=>'all']));
        $this->assertJson($this->subscribers->all());
    }
    
    public function testShowSubscriber()
    {
        $this->subscribers->expects($this->any())->method('show')->willReturn(json_encode(['subscriber'=>'show']));
        $this->assertJson($this->subscribers->show());
    }
    
    public function testPostSubscriber()
    {
        $this->subscribers->expects($this->any())->method('create')->willReturn(json_encode(['subscriber'=>'create']));
        $this->assertJson($this->subscribers->create([]));
    }
    
    public function testPutSubscriber()
    {
        $this->subscribers->expects($this->any())->method('update')->willReturn(json_encode(['subscriber'=>'update']));
        $this->assertJson($this->subscribers->update(1,[]));
    }
    
    public function testDeleteSubscriber()
    {
        $this->subscribers->expects($this->any())->method('remove')->willReturn(json_encode(['subscriber'=>'remove']));
        $this->assertJson($this->subscribers->remove(1));
    }
}
