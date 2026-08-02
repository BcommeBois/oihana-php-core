<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\diffInHours;

use PHPUnit\Framework\TestCase;

class DiffInHoursTest extends TestCase
{
    public function testCountsCompleteHours() : void
    {
        $a = new DateTimeImmutable( '2026-01-01 10:00:00' ) ;
        $b = new DateTimeImmutable( '2026-01-01 15:30:00' ) ;
        $this->assertSame( 5 , diffInHours( $a , $b ) ) ;
    }

    public function testTruncatesTowardsZeroRatherThanRoundingOrFlooring() : void
    {
        $a = new DateTimeImmutable( '2026-01-01 00:00:00' ) ;
        $b = new DateTimeImmutable( '2026-01-01 01:59:00' ) ;
        $this->assertSame( 1 , diffInHours( $a , $b ) ) ;
    }

    public function testIsNegativeWhenBIsBeforeA() : void
    {
        $a = new DateTimeImmutable( '2026-01-01 15:30:00' ) ;
        $b = new DateTimeImmutable( '2026-01-01 10:00:00' ) ;
        $this->assertSame( -5 , diffInHours( $a , $b ) ) ;
    }

    public function testIsZeroForTheSameInstant() : void
    {
        $a = new DateTimeImmutable( '2026-01-01 10:00:00' ) ;
        $this->assertSame( 0 , diffInHours( $a , $a ) ) ;
    }

    public function testIsElapsedTimeNotCalendarTimeAcrossADstTransition() : void
    {
        // Same span as DiffInDaysTest::testIsCalendarAwareAcrossADstTransition() — 2
        // calendar days there, but only 47 real hours here : the spring-forward gap on
        // 2026-03-29 shortens that day by one hour.
        $a = new DateTimeImmutable( '2026-03-28 00:00:00' , new DateTimeZone( 'Europe/Paris' ) ) ;
        $b = new DateTimeImmutable( '2026-03-30 00:00:00' , new DateTimeZone( 'Europe/Paris' ) ) ;
        $this->assertSame( 47 , diffInHours( $a , $b ) ) ;
    }
}
