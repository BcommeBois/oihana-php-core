<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class HasStringKeysTest extends TestCase
{
    public function testAllKeysAreStrings()
    {
        $array = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertTrue(hasStringKeys($array));
    }

    public function testSomeKeysAreNotStrings()
    {
        $array = [1 => 'a', 2 => 'b', 'c' => 'c'];
        $this->assertFalse(hasStringKeys($array));
    }

    public function testEmptyArray()
    {
        $array = [];
        $this->assertTrue(hasStringKeys($array));
    }

    public function testMixedKeys()
    {
        $array = ['a' => 1, 2 => 'b', 'c' => 3];
        $this->assertFalse(hasStringKeys($array));
    }

    public function testStringKeysRepresentingIntegers()
    {
        $array = ['1' => 'a', '2' => 'b', '3' => 'c'];
        $this->assertFalse(hasStringKeys($array));
    }
}
