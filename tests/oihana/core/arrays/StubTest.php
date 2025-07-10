<?php

namespace oihana\core\arrays ;

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
