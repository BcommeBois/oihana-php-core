<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\addYears;

use PHPUnit\Framework\TestCase;

class AddYearsTest extends TestCase
{
    public function testAddsYears() : void
    {
        $result = addYears( new DateTimeImmutable( '2026-01-15' ) , 1 ) ;
        $this->assertSame( '2027-01-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testSubtractsWithNegativeYears() : void
    {
        $result = addYears( new DateTimeImmutable( '2026-01-15' ) , -1 ) ;
        $this->assertSame( '2025-01-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testZeroYearsKeepsSameDate() : void
    {
        $result = addYears( new DateTimeImmutable( '2026-01-15' ) , 0 ) ;
        $this->assertSame( '2026-01-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testClampsALeapDayOntoANonLeapYear() : void
    {
        $result = addYears( new DateTimeImmutable( '2028-02-29' ) , 1 ) ;
        $this->assertSame( '2029-02-28' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testKeepsALeapDayWhenLandingOnAnotherLeapYear() : void
    {
        $result = addYears( new DateTimeImmutable( '2024-02-29' ) , 4 ) ;
        $this->assertSame( '2028-02-29' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-15' ) ;
        $result = addYears( $source , 1 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-15' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2027-01-15' , $result->format( 'Y-m-d' ) ) ;
    }
}
