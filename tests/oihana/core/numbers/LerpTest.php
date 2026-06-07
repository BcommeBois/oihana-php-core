<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\lerp;

use PHPUnit\Framework\TestCase;

class LerpTest extends TestCase
{
    public function testReturnsStartWhenFactorIsZero() : void
    {
        $this->assertSame( 0.0 , lerp( 0.0 , 10.0 , 0.0 ) ) ;
    }

    public function testReturnsEndWhenFactorIsOne() : void
    {
        $this->assertSame( 10.0 , lerp( 0.0 , 10.0 , 1.0 ) ) ;
    }

    public function testReturnsMidpointAtHalf() : void
    {
        $this->assertSame( 5.0 , lerp( 0.0 , 10.0 , 0.5 ) ) ;
    }

    public function testInterpolatesDescendingRange() : void
    {
        $this->assertSame( 7.5 , lerp( 10.0 , 0.0 , 0.25 ) ) ;
    }

    public function testExtrapolatesBeyondRange() : void
    {
        $this->assertSame( 20.0 , lerp( 0.0 , 10.0 , 2.0 ) ) ;
        $this->assertSame( -10.0 , lerp( 0.0 , 10.0 , -1.0 ) ) ;
    }

    public function testWorksWithNegativeBounds() : void
    {
        $this->assertSame( 0.0 , lerp( -5.0 , 5.0 , 0.5 ) ) ;
    }
}
