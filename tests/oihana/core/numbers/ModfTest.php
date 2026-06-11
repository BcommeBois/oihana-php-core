<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\modf;

use PHPUnit\Framework\TestCase;

class ModfTest extends TestCase
{
    public function testSplitsPositiveNumber() : void
    {
        $this->assertSame( [ 1.0 , 0.5 ] , modf( 1.5 ) ) ;
    }

    public function testSplitsNegativeNumberTruncatingTowardZero() : void
    {
        $this->assertSame( [ -1.0 , -0.5 ] , modf( -1.5 ) ) ;
    }

    public function testReturnsZeroFractionOnWholeNumbers() : void
    {
        $this->assertSame( [ 3.0  , 0.0 ] , modf( 3.0 ) ) ;
        $this->assertSame( [ -3.0 , 0.0 ] , modf( -3.0 ) ) ;
        $this->assertSame( [ 0.0  , 0.0 ] , modf( 0.0 ) ) ;
    }

    public function testKeepsTheSignOnTheFractionalPartBelowOne() : void
    {
        [ $integral , $fractional ] = modf( -0.25 ) ;
        $this->assertSame( -0.0  , $integral ) ;
        $this->assertSame( -0.25 , $fractional ) ;
    }

    public function testFractionalPartIsExactWhenRepresentable() : void
    {
        $this->assertSame( [ 2.0 , 0.75 ] , modf( 2.75 ) ) ;
        $this->assertSame( [ -2.0 , -0.75 ] , modf( -2.75 ) ) ;
    }

    public function testReassemblingBothPartsRestoresTheNumber() : void
    {
        foreach ( [ 1.5 , -1.5 , 12.125 , -0.875 , 123456.789 , -98765.4321 ] as $number )
        {
            [ $integral , $fractional ] = modf( $number ) ;
            $this->assertSame( $number , $integral + $fractional ) ;
        }
    }

    public function testHandlesNumbersBeyondIntPrecision() : void
    {
        $big = 1.0e+18 ; // exactly representable, far above any fractional resolution
        $this->assertSame( [ $big , 0.0 ] , modf( $big ) ) ;
        $this->assertSame( [ -$big , 0.0 ] , modf( -$big ) ) ;
    }

    public function testInfinityYieldsZeroFraction() : void
    {
        $this->assertSame( [ INF , 0.0 ] , modf( INF ) ) ;
        $this->assertSame( [ -INF , 0.0 ] , modf( -INF ) ) ;
    }

    public function testNanPropagatesToBothParts() : void
    {
        [ $integral , $fractional ] = modf( NAN ) ;
        $this->assertNan( $integral ) ;
        $this->assertNan( $fractional ) ;
    }

    public function testMatchesTheCModfContractAgainstFmod() : void
    {
        foreach ( [ 7.25 , -7.25 , 0.5 , -0.5 ] as $number )
        {
            [ , $fractional ] = modf( $number ) ;
            $this->assertSame( fmod( $number , 1.0 ) , $fractional ) ;
        }
    }
}
