<?php

namespace tests\oihana\core\maths;

use InvalidArgumentException;

use function oihana\core\maths\stddev;

use PHPUnit\Framework\TestCase;

class StddevTest extends TestCase
{
    private const SAMPLE = [ 2 , 4 , 4 , 4 , 5 , 5 , 7 , 9 ] ;

    public function testPopulationStandardDeviation() : void
    {
        $this->assertSame( 2.0 , stddev( self::SAMPLE ) ) ;
    }

    public function testSampleStandardDeviation() : void
    {
        $this->assertEqualsWithDelta( sqrt( 32 / 7 ) , stddev( self::SAMPLE , true ) , 1e-9 ) ;
    }

    public function testZeroForIdenticalValues() : void
    {
        $this->assertSame( 0.0 , stddev( [ 3 , 3 , 3 ] ) ) ;
    }

    public function testThrowsOnEmptyArray() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        stddev( [] ) ;
    }

    public function testThrowsOnSampleWithSingleValue() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        stddev( [ 7 ] , true ) ;
    }
}
