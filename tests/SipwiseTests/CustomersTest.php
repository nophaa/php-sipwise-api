<?php

namespace Sipwise\Tests;

use PHPUnit\Framework\TestCase;
use Sipwise\Api\Customers;

/**
 * Description of CustomersTest.
 *
 * @author Nova Kurniawan <novadwikurniawan@gmai.com>
 */
class CustomersTest extends TestCase
{
    protected $customers;

    public function setUp()
    {
        $this->customers = $this->createMock(Customers::class);
    }

    public function tearDown()
    {
        unset($this->customers);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Customers::class, $this->customers);
    }

    public function testAllCustomer()
    {
        $this->customers->expects($this->any())->method('all')->willReturn(json_encode(['customer'=>'all']));
        $this->assertJson($this->customers->all());
    }

    public function testShowCustomer()
    {
        $this->customers->expects($this->any())->method('show')->willReturn(json_encode(['customer'=>'show']));
        $this->assertJson($this->customers->show());
    }

    public function testPostCustomer()
    {
        $this->customers->expects($this->any())->method('create')->willReturn(json_encode(['customer'=>'create']));
        $this->assertJson($this->customers->create([]));
    }

    public function testPutCustomer()
    {
        $this->customers->expects($this->any())->method('update')->willReturn(json_encode(['customer'=>'update']));
        $this->assertJson($this->customers->update(1, []));
    }

    public function testDeleteCustomer()
    {
        $this->customers->expects($this->any())->method('remove')->willReturn(json_encode(['customer'=>'remove']));
        $this->assertJson($this->customers->remove(1));
    }
}
