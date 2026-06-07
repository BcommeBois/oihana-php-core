<?php

namespace oihana\core\numbers ;

use PHPUnit\Framework\TestCase;

class ClipTest extends TestCase
{
    public function testReturnsMinWhenBelowRange() : void
    {
        $this->assertSame( 5 , clip( 4 , 5 , 10 ) ) ;
    }

    public function testReturnsMaxWhenAboveRange() : void
    {
        $this->assertSame( 10 , clip( 12 , 5 , 10 ) ) ;
    }

    public function testReturnsValueWhenInsideRange() : void
    {
        $this->assertSame( 6 , clip( 6 , 5 , 10 ) ) ;
    }

    public function testReturnsBoundsOnExactEdges() : void
    {
        $this->assertSame( 5 , clip( 5 , 5 , 10 ) ) ;
        $this->assertSame( 10 , clip( 10 , 5 , 10 ) ) ;
    }

    public function testWorksWithFloats() : void
    {
        $this->assertSame( 1.5 , clip( 0.5 , 1.5 , 3.5 ) ) ;
        $this->assertSame( 3.5 , clip( 9.9 , 1.5 , 3.5 ) ) ;
        $this->assertSame( 2.5 , clip( 2.5 , 1.5 , 3.5 ) ) ;
    }

    public function testWorksWithNegativeRange() : void
    {
        $this->assertSame( -5 , clip( -10 , -5 , 5 ) ) ;
        $this->assertSame( 5 , clip( 10 , -5 , 5 ) ) ;
        $this->assertSame( 0 , clip( 0 , -5 , 5 ) ) ;
    }
}
