<?php

namespace tests\oihana\core\date;

use oihana\core\date\DurationUnit;

use PHPUnit\Framework\TestCase;

class DurationUnitTest extends TestCase
{
    public function testConstantsValues() : void
    {
        $this->assertSame( 'd' , DurationUnit::DAY ) ;
        $this->assertSame( 'h' , DurationUnit::HOUR ) ;
        $this->assertSame( 'm' , DurationUnit::MINUTE ) ;
        $this->assertSame( 's' , DurationUnit::SECOND ) ;
    }

    public function testAllReturnsEverySuffixLargestToSmallest() : void
    {
        $this->assertSame(
            [ DurationUnit::DAY , DurationUnit::HOUR , DurationUnit::MINUTE , DurationUnit::SECOND ] ,
            DurationUnit::all()
        ) ;
    }

    public function testIsValidAcceptsKnownSuffixes() : void
    {
        $this->assertTrue( DurationUnit::isValid( 'd' ) ) ;
        $this->assertTrue( DurationUnit::isValid( 'h' ) ) ;
        $this->assertTrue( DurationUnit::isValid( 'm' ) ) ;
        $this->assertTrue( DurationUnit::isValid( 's' ) ) ;
    }

    public function testIsValidRejectsUnknownSuffix() : void
    {
        $this->assertFalse( DurationUnit::isValid( 'y' ) ) ;
    }

    public function testIsValidIsCaseSensitive() : void
    {
        $this->assertFalse( DurationUnit::isValid( 'D' ) ) ;
    }
}
