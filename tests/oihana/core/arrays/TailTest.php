<?php
namespace oihana\core\arrays;

use PHPUnit\Framework\TestCase;

class TailTest extends TestCase
{
    public function testTailWithNonEmptyArray()
    {
        $input = [2, 3, 4];
        $result = tail($input);
        $this->assertEquals([3, 4], $result);
    }

    public function testTailWithEmptyArray()
    {
        $input = [];
        $result = tail($input);
        $this->assertEquals([], $result);
    }

    public function testTailWithSingleElementArray()
    {
        $input = [42];
        $result = tail($input);
        $this->assertEquals([], $result);
    }

    public function testTailWithAssociativeArray()
    {
        $input = ['a' => 1, 'b' => 2, 'c' => 3];
        $result = tail($input);
        $this->assertEquals(['b' => 2, 'c' => 3], $result);
    }

    public function testTailWithStringKeys()
    {
        $input = ['first' => 1, 'second' => 2, 'third' => 3];
        $result = tail($input);
        $this->assertEquals(['second' => 2, 'third' => 3], $result);
    }

    public function testTailWithMixedKeys()
    {
        $input = [0 => 'a', 'b' => 'b', 'c' => 'c'];
        $result = tail($input);
        $this->assertEquals(['b' => 'b', 'c' => 'c'], $result);
    }
}
