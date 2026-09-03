<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\toInt;

use PHPUnit\Framework\TestCase;

class ToIntTest extends TestCase
{
    public function testReturnsIntUnchanged() : void
    {
        $this->assertSame( 42 , toInt( 42 ) ) ;
        $this->assertSame( -7 , toInt( -7 ) ) ;
    }

    public function testCastsNumericString() : void
    {
        $this->assertSame( 42 , toInt( '42' ) ) ;
        $this->assertSame( -7 , toInt( '-7' ) ) ;
    }

    public function testCastsFloat() : void
    {
        $this->assertSame( 4 , toInt( 4.9 ) ) ;
    }

    public function testFallsBackToZero() : void
    {
        $this->assertSame( 0 , toInt( 'abc' ) ) ;
        $this->assertSame( 0 , toInt( null ) ) ;
        $this->assertSame( 0 , toInt( [] ) ) ;
        $this->assertSame( 0 , toInt( false ) ) ;
    }
}
