<?php

namespace tests\oihana\core\maths;

use InvalidArgumentException;

use function oihana\core\maths\median;

use PHPUnit\Framework\TestCase;

class MedianTest extends TestCase
{
    public function testOddCountReturnsMiddle() : void
    {
        $this->assertSame( 2.0 , median( [ 3 , 1 , 2 ] ) ) ;
    }

    public function testEvenCountAveragesTwoCentralValues() : void
    {
        $this->assertSame( 2.5 , median( [ 4 , 1 , 3 , 2 ] ) ) ;
    }

    public function testSingleValue() : void
    {
        $this->assertSame( 5.0 , median( [ 5 ] ) ) ;
    }

    public function testWorksWithUnsortedFloats() : void
    {
        $this->assertSame( 3.3 , median( [ 9.9 , 1.1 , 3.3 ] ) ) ;
    }

    public function testThrowsOnEmptyArray() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        median( [] ) ;
    }
}
