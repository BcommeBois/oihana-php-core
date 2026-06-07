<?php

namespace tests\oihana\core\numbers;

use function oihana\core\numbers\isEven;

use PHPUnit\Framework\TestCase;

class IsEvenTest extends TestCase
{
    public function testReturnsTrueForEven() : void
    {
        $this->assertTrue( isEven( 4 ) ) ;
        $this->assertTrue( isEven( 0 ) ) ;
        $this->assertTrue( isEven( -2 ) ) ;
    }

    public function testReturnsFalseForOdd() : void
    {
        $this->assertFalse( isEven( 7 ) ) ;
        $this->assertFalse( isEven( 1 ) ) ;
        $this->assertFalse( isEven( -3 ) ) ;
    }
}
