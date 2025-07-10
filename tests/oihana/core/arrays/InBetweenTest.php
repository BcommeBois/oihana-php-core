<?php
namespace oihana\core\arrays;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InBetweenTest extends TestCase
{
    public function testInBetweenWithMultipleElements()
    {
        $result = inBetween(['a', 'b', 'c'], '/');
        $this->assertEquals(['a', '/', 'b', '/', 'c'], $result);
    }

    public function testInBetweenWithEmptyArray()
    {
        $result = inBetween([], '/');
        $this->assertEquals([], $result);
    }

    public function testInBetweenWithSingleElement()
    {
        $result = inBetween(['a'], '/');
        $this->assertEquals(['a'], $result);
    }

    public function testInBetweenWithNullElement()
    {
        $result = inBetween(['a', 'b', 'c'], null);
        $this->assertEquals(['a', null, 'b', null, 'c'], $result);
    }
        
    public function testInBetweenWithDifferentElementTypes()
    {
        $input = ['a', 'b', 'c'];
        $element = ['x', 'y']; // Éléments complexes
        $result = inBetween($input, $element);
        $expected = ['a', ['x', 'y'], 'b', ['x', 'y'], 'c'];
        $this->assertEquals($expected, $result);
    }

    public function testInBetweenWithTwoElements()
    {
        $result = inBetween([1, 2], '/');
        $this->assertEquals([1, '/', 2], $result);
    }
}
