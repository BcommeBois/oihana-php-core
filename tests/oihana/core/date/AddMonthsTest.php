<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\addMonths;
use function oihana\core\date\daysInGregorianMonth;

use PHPUnit\Framework\TestCase;

class AddMonthsTest extends TestCase
{
    public function testAddsMonths() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-01-15' ) , 1 ) ;
        $this->assertSame( '2026-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testSubtractsWithNegativeMonths() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-03-15' ) , -1 ) ;
        $this->assertSame( '2026-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testZeroMonthsKeepsSameDate() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-01-15' ) , 0 ) ;
        $this->assertSame( '2026-01-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testClampsDayOverflowToLastDayOfTargetMonth() : void
    {
        // 2026 is not a leap year : February has 28 days.
        $result = addMonths( new DateTimeImmutable( '2026-01-31' ) , 1 ) ;
        $this->assertSame( '2026-02-28' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testClampsToTheLeapDayInALeapYear() : void
    {
        $result = addMonths( new DateTimeImmutable( '2024-01-31' ) , 1 ) ;
        $this->assertSame( '2024-02-29' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testClampsOnTheBackwardsDirectionToo() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-03-31' ) , -1 ) ;
        $this->assertSame( '2026-02-28' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testCarriesTheYearForwardsAcrossDecember() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-11-15' ) , 3 ) ;
        $this->assertSame( '2027-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testCarriesTheYearBackwardsAcrossJanuary() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-02-15' ) , -3 ) ;
        $this->assertSame( '2025-11-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testPreservesTheWallClockTimeOfDay() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-01-15 10:30:45.123456' ) , 1 ) ;
        $this->assertSame( '2026-02-15 10:30:45.123456' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-01-15' , new DateTimeZone( 'Europe/Paris' ) ) , 1 ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-15' ) ;
        $result = addMonths( $source , 1 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-15' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2026-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testHandlesAShiftLargerThanOneYear() : void
    {
        $result = addMonths( new DateTimeImmutable( '2026-01-15' ) , 25 ) ;
        $this->assertSame( '2028-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testHandlesAShiftIntoAYearNearZeroWithoutCrashing() : void
    {
        // The naive "new DateTimeImmutable( \"$year-$month-01\" )" approach fails on
        // several short negative-year forms ; addMonths() must not go through it.
        $result = addMonths( new DateTimeImmutable( '0005-06-15' ) , -100 ) ;
        $this->assertSame( '-0003-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testDaysInGregorianMonthFollowsTheLeapYearRule() : void
    {
        $this->assertSame( 28 , daysInGregorianMonth( 2026 , 2 ) ) ;
        $this->assertSame( 29 , daysInGregorianMonth( 2024 , 2 ) ) ; // divisible by 4
        $this->assertSame( 28 , daysInGregorianMonth( 1900 , 2 ) ) ; // divisible by 100, not 400
        $this->assertSame( 29 , daysInGregorianMonth( 2000 , 2 ) ) ; // divisible by 400
        $this->assertSame( 30 , daysInGregorianMonth( 2026 , 4 ) ) ;
        $this->assertSame( 31 , daysInGregorianMonth( 2026 , 1 ) ) ;
    }

    public function testDaysInGregorianMonthHandlesNegativeAndZeroYears() : void
    {
        $this->assertSame( 29 , daysInGregorianMonth( -4 , 2 ) ) ;
        $this->assertSame( 28 , daysInGregorianMonth( -3 , 2 ) ) ;
        $this->assertSame( 28 , daysInGregorianMonth( -100 , 2 ) ) ;
        $this->assertSame( 29 , daysInGregorianMonth( -400 , 2 ) ) ;
        $this->assertSame( 29 , daysInGregorianMonth( 0 , 2 ) ) ;
    }
}
