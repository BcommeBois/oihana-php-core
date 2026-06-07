<?php

namespace tests\oihana\core\numbers;

use InvalidArgumentException;

use function oihana\core\numbers\mapRange;

use PHPUnit\Framework\TestCase;

class MapRangeTest extends TestCase
{
    public function testRemapsMidpoint() : void
    {
        $this->assertSame( 50.0 , mapRange( 5.0 , 0.0 , 10.0 , 0.0 , 100.0 ) ) ;
    }

    public function testRemapsLowerBound() : void
    {
        $this->assertSame( 0.0 , mapRange( 0.0 , 0.0 , 10.0 , 0.0 , 100.0 ) ) ;
    }

    public function testRemapsUpperBound() : void
    {
        $this->assertSame( 100.0 , mapRange( 10.0 , 0.0 , 10.0 , 0.0 , 100.0 ) ) ;
    }

    public function testRemapsNegativeInputRange() : void
    {
        $this->assertSame( 127.5 , mapRange( 0.0 , -1.0 , 1.0 , 0.0 , 255.0 ) ) ;
    }

    public function testRemapsOutsideOutputRange() : void
    {
        $this->assertSame( 150.0 , mapRange( 15.0 , 0.0 , 10.0 , 0.0 , 100.0 ) ) ;
    }

    public function testRemapsToInvertedOutputRange() : void
    {
        $this->assertSame( 75.0 , mapRange( 2.5 , 0.0 , 10.0 , 100.0 , 0.0 ) ) ;
    }

    public function testThrowsOnDegenerateInputRange() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        mapRange( 5.0 , 10.0 , 10.0 , 0.0 , 100.0 ) ;
    }
}
