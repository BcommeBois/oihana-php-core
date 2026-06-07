<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\percentage;

use PHPUnit\Framework\TestCase;

class PercentageTest extends TestCase
{
    public function testComputesPercentage() : void
    {
        $this->assertSame( 12.5 , percentage( 25 , 200 ) ) ;
    }

    public function testComputesFullAndEmpty() : void
    {
        $this->assertSame( 100.0 , percentage( 50 , 50 ) ) ;
        $this->assertSame( 0.0 , percentage( 0 , 50 ) ) ;
    }

    public function testWorksWithFloats() : void
    {
        $this->assertSame( 50.0 , percentage( 1.5 , 3.0 ) ) ;
    }

    public function testGuardsAgainstDivisionByZero() : void
    {
        $this->assertSame( 0.0 , percentage( 5 , 0 ) ) ;
        $this->assertSame( 0.0 , percentage( 0 , 0 ) ) ;
    }

    public function testReturnsValueAboveHundredWhenPartExceedsTotal() : void
    {
        $this->assertSame( 150.0 , percentage( 3 , 2 ) ) ;
    }
}
