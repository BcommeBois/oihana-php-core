<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\sign;

use PHPUnit\Framework\TestCase;

class SignTest extends TestCase
{
    public function testReturnsMinusOneForNegative() : void
    {
        $this->assertSame( -1 , sign( -42 ) ) ;
        $this->assertSame( -1 , sign( -0.001 ) ) ;
    }

    public function testReturnsZeroForZero() : void
    {
        $this->assertSame( 0 , sign( 0 ) ) ;
        $this->assertSame( 0 , sign( 0.0 ) ) ;
    }

    public function testReturnsOneForPositive() : void
    {
        $this->assertSame( 1 , sign( 42 ) ) ;
        $this->assertSame( 1 , sign( 3.14 ) ) ;
    }
}
