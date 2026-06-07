<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\isOdd;

use PHPUnit\Framework\TestCase;

class IsOddTest extends TestCase
{
    public function testReturnsTrueForOdd() : void
    {
        $this->assertTrue( isOdd( 7 ) ) ;
        $this->assertTrue( isOdd( 1 ) ) ;
        $this->assertTrue( isOdd( -3 ) ) ;
    }

    public function testReturnsFalseForEven() : void
    {
        $this->assertFalse( isOdd( 4 ) ) ;
        $this->assertFalse( isOdd( 0 ) ) ;
        $this->assertFalse( isOdd( -2 ) ) ;
    }
}
