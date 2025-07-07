<?php

namespace oihana\core\strings ;

use PHPUnit\Framework\TestCase;

class ToStringTest extends TestCase
{
    public function testNullReturnsEmptyString()
    {
        $this->assertSame('' , toString(null) ) ;
    }

    public function testStringIsReturnedAsIs()
    {
        $this->assertSame('hello', toString('hello') ) ;
    }

    public function testArrayIsFlattenedToCommaSeparatedString()
    {
        $this->assertSame('1,2,3', toString([1, 2, 3]));
        $this->assertSame('1,,3', toString([1, null, 3]));
        $this->assertSame('a,b,c', toString(['a', 'b', 'c']));
        $this->assertSame('1,2,3,4', toString([1, [2, 3], 4])); // Note: PHP won't flatten nested arrays
    }

    public function testBooleanValues()
    {
        $this->assertSame('1', toString(true));
        $this->assertSame('', toString(false)); // (string) false === ""
    }

    public function testNumbers()
    {
        $this->assertSame('42', toString(42));
        $this->assertSame('3.14', toString(3.14));
    }

    public function testNegativeZero()
    {
        $negZero = -0.0;
        $this->assertSame('-0', toString($negZero));
    }

    public function testPositiveZero()
    {
        $posZero = 0.0;
        $this->assertSame('0', toString($posZero));
    }

    public function testEmptyArray()
    {
        $this->assertSame('', toString([]));
    }

    public function testNestedArrayDoesNotFlattenDeeply()
    {
        $this->assertSame('1,2', toString([1, [2]]));
        $this->assertSame('1,2,3', toString([1, [2, 3]]));
        $this->assertSame('a,b,c,d', toString(['a', ['b', ['c', 'd']]]));
    }
}