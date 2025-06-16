<?php

namespace oihana\core\arrays ;

use PHPUnit\Framework\TestCase;

class HasIntKeysTest extends TestCase
{
    public function testAllKeysAreIntegers()
    {
        $array = [0 => 'a', 1 => 'b', 2 => 'c'];
        $this->assertTrue(hasIntKeys($array));
    }

    public function testSomeKeysAreNotIntegers()
    {
        $array = ['a' => 'a', 'b' => 'b', 'c' => 'c'];
        $this->assertFalse(hasIntKeys($array));
    }

    public function testEmptyArray()
    {
        $array = [];
        $this->assertTrue(hasIntKeys($array));
    }

    public function testMixedKeys()
    {
        $array = [1 => 'a', 'b' => 'b', 3 => 'c'];
        $this->assertFalse(hasIntKeys($array));
    }

    public function testStringKeysRepresentingIntegers()
    {
        $array = ['1' => 'a', '2' => 'b', '3' => 'c'];
        $this->assertTrue(hasIntKeys($array));
    }
}
