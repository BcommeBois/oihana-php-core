<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\diffInDays;

use PHPUnit\Framework\TestCase;

class DiffInDaysTest extends TestCase
{
    public function testCountsTheTotalNumberOfDaysNotTheDayOfMonthComponent() : void
    {
        // The DateInterval::$d trap : over this 34-day gap, $a->diff($b)->d reads 3
        // (days within the month), not 34.
        $a = new DateTimeImmutable( '2026-01-01' ) ;
        $b = new DateTimeImmutable( '2026-02-04' ) ;
        $this->assertSame( 34 , diffInDays( $a , $b ) ) ;
    }

    public function testIsNegativeWhenBIsBeforeA() : void
    {
        $a = new DateTimeImmutable( '2026-02-04' ) ;
        $b = new DateTimeImmutable( '2026-01-01' ) ;
        $this->assertSame( -34 , diffInDays( $a , $b ) ) ;
    }

    public function testIsZeroForTheSameInstant() : void
    {
        $a = new DateTimeImmutable( '2026-01-01 10:00:00' ) ;
        $this->assertSame( 0 , diffInDays( $a , $a ) ) ;
    }

    public function testIsCalendarAwareAcrossADstTransition() : void
    {
        // 2026-03-29 is the spring-forward day in Europe/Paris (one hour short).
        // The calendar gap is still 2 days, unlike diffInHours() (see its own test).
        $a = new DateTimeImmutable( '2026-03-28 00:00:00' , new DateTimeZone( 'Europe/Paris' ) ) ;
        $b = new DateTimeImmutable( '2026-03-30 00:00:00' , new DateTimeZone( 'Europe/Paris' ) ) ;
        $this->assertSame( 2 , diffInDays( $a , $b ) ) ;
    }
}
