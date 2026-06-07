<?php

namespace tests\oihana\core\maths;

use InvalidArgumentException;

use function oihana\core\maths\mean;

use PHPUnit\Framework\TestCase;

class MeanTest extends TestCase
{
    public function testComputesMean() : void
    {
        $this->assertSame( 2.5 , mean( [ 1 , 2 , 3 , 4 ] ) ) ;
    }

    public function testReturnsFloatForIntegerResult() : void
    {
        $this->assertSame( 4.0 , mean( [ 2 , 4 , 6 ] ) ) ;
    }

    public function testWorksWithFloats() : void
    {
        $this->assertSame( 1.5 , mean( [ 1.0 , 2.0 ] ) ) ;
    }

    public function testSingleValue() : void
    {
        $this->assertSame( 7.0 , mean( [ 7 ] ) ) ;
    }

    public function testThrowsOnEmptyArray() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        mean( [] ) ;
    }
}
