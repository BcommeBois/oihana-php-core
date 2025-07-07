<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class FlattenTest extends TestCase
{
    public function testEmptyArray()
    {
        $this->assertSame([], flatten([]));
    }

    public function testFlatArray()
    {
        $this->assertSame([1, 2, 3], flatten([1, 2, 3]));
    }

    public function testNestedArray()
    {
        $input = [1, [2, 3], 4];
        $expected = [1, 2, 3, 4];
        $this->assertSame($expected, flatten($input));
    }

    public function testDeepNestedArray()
    {
        $input = [1, [2, [3, [4, 5]]], 6];
        $expected = [1, 2, 3, 4, 5, 6];
        $this->assertSame($expected, flatten($input));
    }

    public function testArrayWithEmptySubarrays()
    {
        $input = [1, [], [2, []], 3];
        $expected = [1, 2, 3];
        $this->assertSame($expected, flatten($input));
    }

    public function testArrayWithDifferentTypes()
    {
        $input = ['a', [true, [null, 3.14]], false];
        $expected = ['a', true, null, 3.14, false];
        $this->assertSame($expected, flatten($input));
    }
}
