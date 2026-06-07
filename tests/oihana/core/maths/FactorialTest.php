<?php

namespace tests\oihana\core\maths;

use InvalidArgumentException;

use function oihana\core\maths\factorial;

use PHPUnit\Framework\TestCase;

class FactorialTest extends TestCase
{
    public function testFactorialOfZeroIsOne() : void
    {
        $this->assertSame( 1 , factorial( 0 ) ) ;
    }

    public function testFactorialOfOneIsOne() : void
    {
        $this->assertSame( 1 , factorial( 1 ) ) ;
    }

    public function testSmallFactorials() : void
    {
        $this->assertSame( 120 , factorial( 5 ) ) ;
        $this->assertSame( 3628800 , factorial( 10 ) ) ;
    }

    public function testLargestExactFactorial() : void
    {
        $this->assertSame( 2432902008176640000 , factorial( 20 ) ) ;
    }

    public function testThrowsOnNegative() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        factorial( -1 ) ;
    }

    public function testThrowsAboveTwenty() : void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        factorial( 21 ) ;
    }
}
