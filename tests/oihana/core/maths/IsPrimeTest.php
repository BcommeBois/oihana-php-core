<?php

namespace tests\oihana\core\maths;

use function oihana\core\maths\isPrime;

use PHPUnit\Framework\TestCase;

class IsPrimeTest extends TestCase
{
    public function testSmallPrimes() : void
    {
        $this->assertTrue( isPrime( 2 ) ) ;
        $this->assertTrue( isPrime( 3 ) ) ;
        $this->assertTrue( isPrime( 5 ) ) ;
        $this->assertTrue( isPrime( 7 ) ) ;
    }

    public function testLargerPrime() : void
    {
        $this->assertTrue( isPrime( 97 ) ) ;
        $this->assertTrue( isPrime( 7919 ) ) ;
    }

    public function testComposites() : void
    {
        $this->assertFalse( isPrime( 4 ) ) ;
        $this->assertFalse( isPrime( 9 ) ) ;
        $this->assertFalse( isPrime( 25 ) ) ;
        $this->assertFalse( isPrime( 91 ) ) ; // 7 * 13
    }

    public function testBelowTwoIsNotPrime() : void
    {
        $this->assertFalse( isPrime( 1 ) ) ;
        $this->assertFalse( isPrime( 0 ) ) ;
        $this->assertFalse( isPrime( -7 ) ) ;
    }
}
