<?php

namespace tests\oihana\core\date;

use function oihana\core\date\isLeapYear;

use PHPUnit\Framework\TestCase;

class IsLeapYearTest extends TestCase
{
    public function testTrueWhenDivisibleByFourAndNotByOneHundred() : void
    {
        $this->assertTrue( isLeapYear( 2024 ) ) ;
        $this->assertTrue( isLeapYear( 2028 ) ) ;
    }

    public function testFalseWhenNotDivisibleByFour() : void
    {
        $this->assertFalse( isLeapYear( 2025 ) ) ;
        $this->assertFalse( isLeapYear( 2026 ) ) ;
    }

    public function testFalseWhenDivisibleByOneHundredButNotByFourHundred() : void
    {
        $this->assertFalse( isLeapYear( 1900 ) ) ;
        $this->assertFalse( isLeapYear( 2100 ) ) ;
    }

    public function testTrueWhenDivisibleByFourHundred() : void
    {
        $this->assertTrue( isLeapYear( 2000 ) ) ;
        $this->assertTrue( isLeapYear( 1600 ) ) ;
    }

    public function testHandlesNegativeAndZeroYears() : void
    {
        $this->assertTrue ( isLeapYear( -4 ) ) ;
        $this->assertFalse( isLeapYear( -3 ) ) ;
        $this->assertFalse( isLeapYear( -100 ) ) ;
        $this->assertTrue ( isLeapYear( -400 ) ) ;
        $this->assertTrue ( isLeapYear( 0 ) ) ;
    }
}
