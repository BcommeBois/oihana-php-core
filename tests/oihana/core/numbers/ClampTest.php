<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\clamp;

use PHPUnit\Framework\TestCase;

class ClampTest extends TestCase
{
    public function testReturnsMinWhenBelowRange() : void
    {
        $this->assertSame( 5 , clamp( 4 , 5 , 10 ) ) ;
    }

    public function testReturnsMaxWhenAboveRange() : void
    {
        $this->assertSame( 10 , clamp( 12 , 5 , 10 ) ) ;
    }

    public function testReturnsValueWhenInsideRange() : void
    {
        $this->assertSame( 6 , clamp( 6 , 5 , 10 ) ) ;
    }

    public function testReturnsBoundsOnExactEdges() : void
    {
        $this->assertSame( 5 , clamp( 5 , 5 , 10 ) ) ;
        $this->assertSame( 10 , clamp( 10 , 5 , 10 ) ) ;
    }

    public function testWorksWithFloats() : void
    {
        $this->assertSame( 1.5 , clamp( 0.5 , 1.5 , 3.5 ) ) ;
        $this->assertSame( 3.5 , clamp( 9.9 , 1.5 , 3.5 ) ) ;
        $this->assertSame( 2.5 , clamp( 2.5 , 1.5 , 3.5 ) ) ;
    }

    public function testWorksWithNegativeRange() : void
    {
        $this->assertSame( -5 , clamp( -10 , -5 , 5 ) ) ;
        $this->assertSame( 5 , clamp( 10 , -5 , 5 ) ) ;
        $this->assertSame( 0 , clamp( 0 , -5 , 5 ) ) ;
    }
}
