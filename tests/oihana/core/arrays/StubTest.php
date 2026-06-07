<?php

namespace tests\oihana\core\arrays;

use function oihana\core\arrays\stub;

use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{
    public function testStubReturnsEmptyArray()
    {
        $result = stub();

        $this->assertIsArray($result, 'The result should be an array');
        $this->assertEmpty($result, 'The array should be empty');
        $this->assertEquals([], $result, 'The array should be exactly equal to []');
    }
}
