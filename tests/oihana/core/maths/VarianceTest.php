<?php

namespace tests\oihana\core\maths;

use InvalidArgumentException;

use function oihana\core\maths\variance;

use PHPUnit\Framework\TestCase;

class VarianceTest extends TestCase
{
    private const SAMPLE = [ 2 , 4 , 4 , 4 , 5 , 5 , 7 , 9 ] ;

    public function testPopulationVariance() : void
    {
        $this->assertSame( 4.0 , variance( self::SAMPLE ) ) ;
    }

    public function testSampleVariance() : void
    {
        $this->assertEqualsWithDelta( 32 / 7 , variance( self::SAMPLE , true ) , 1e-9 ) ;
    }

    public function testZeroVarianceForIdenticalValues() : void
    {
        $this->assertSame( 0.0 , variance( [ 5 , 5 , 5 ] ) ) ;
    }

    public function testSingleValuePopulationIsZero() : void
    {
        $this->assertSame( 0.0 , variance( [ 42 ] ) ) ;
    }

    public function testThrowsOnEmptyArray() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        variance( [] ) ;
    }

    public function testThrowsOnSampleWithSingleValue() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        variance( [ 1 ] , true ) ;
    }
}
