<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\isNear;

use PHPUnit\Framework\TestCase;

class IsNearTest extends TestCase
{
    public function testWithinTolerance() : void
    {
        $this->assertTrue( isNear( 104 , 100 , 0.05 ) ) ;
        $this->assertTrue( isNear( 96  , 100 , 0.05 ) ) ;
    }

    public function testBeyondTolerance() : void
    {
        $this->assertFalse( isNear( 110 , 100 , 0.05 ) ) ;
        $this->assertFalse( isNear( 90  , 100 , 0.05 ) ) ;
    }

    public function testExactMatch() : void
    {
        $this->assertTrue( isNear( 100 , 100 , 0.0 ) ) ;
    }

    public function testAtExactBoundary() : void
    {
        $this->assertTrue( isNear( 105 , 100 , 0.05 ) ) ;
    }

    public function testWithNegativeTarget() : void
    {
        $this->assertTrue( isNear( -104 , -100 , 0.05 ) ) ;
        $this->assertFalse( isNear( -110 , -100 , 0.05 ) ) ;
    }

    public function testWithZeroTarget() : void
    {
        $this->assertTrue( isNear( 0 , 0 , 0.05 ) ) ;
        $this->assertFalse( isNear( 1 , 0 , 0.05 ) ) ;
    }
}
