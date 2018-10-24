<?php

namespace Sipwise\Tests;

use PHPUnit\Framework\TestCase;
use Sipwise\Api\Calls;

/**
 * Description of callsTest.
 *
 * @author myrepubliclabs
 */
class CallsTest extends TestCase
{
    protected $calls;

    public function setUp()
    {
        $this->calls = $this->createMock(calls::class);
    }

    public function tearDown()
    {
        unset($this->calls);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(Calls::class, $this->calls);
    }

    public function testAllCalls()
    {
        $this->calls->expects($this->any())->method('all')->willReturn(json_encode(['calls'=>'all']));
        $this->assertJson($this->calls->all());
    }
}
