<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\isSameDay;

use PHPUnit\Framework\TestCase;

class IsSameDayTest extends TestCase
{
    public function testTrueForTheSameDayDifferentTimes() : void
    {
        $a = new DateTimeImmutable( '2026-03-15 08:00:00' ) ;
        $b = new DateTimeImmutable( '2026-03-15 23:59:59' ) ;
        $this->assertTrue( isSameDay( $a , $b ) ) ;
    }

    public function testFalseForDifferentDays() : void
    {
        $a = new DateTimeImmutable( '2026-03-15 23:59:59' ) ;
        $b = new DateTimeImmutable( '2026-03-16 00:00:00' ) ;
        $this->assertFalse( isSameDay( $a , $b ) ) ;
    }

    public function testIsSymmetric() : void
    {
        $a = new DateTimeImmutable( '2026-03-15' ) ;
        $b = new DateTimeImmutable( '2026-03-16' ) ;
        $this->assertSame( isSameDay( $a , $b ) , isSameDay( $b , $a ) ) ;
    }

    public function testDefaultsToTheTimezoneCarriedByTheFirstArgument() : void
    {
        // $a is 2026-01-01 01:00 UTC (Jan 1st in UTC, $a's own timezone).
        // $b is 2026-01-01 20:00 UTC, still Jan 1st in UTC : same day by default.
        $a = new DateTimeImmutable( '2026-01-01 01:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $b = new DateTimeImmutable( '2026-01-01 20:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $this->assertTrue( isSameDay( $a , $b ) ) ;
    }

    public function testAnExplicitTimezoneCanFlipTheVerdict() : void
    {
        // Same instants as above, but compared from Tokyo (UTC+9) : $a is 10:00 Jan 1st
        // there, $b is 05:00 Jan 2nd — no longer the same day.
        $a = new DateTimeImmutable( '2026-01-01 01:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $b = new DateTimeImmutable( '2026-01-01 20:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $this->assertFalse( isSameDay( $a , $b , new DateTimeZone( 'Asia/Tokyo' ) ) ) ;
    }

    public function testDoesNotMutateEitherArgument() : void
    {
        $a = new DateTimeImmutable( '2026-01-01 01:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $b = new DateTimeImmutable( '2026-01-01 20:00:00' , new DateTimeZone( 'UTC' ) ) ;
        isSameDay( $a , $b , new DateTimeZone( 'Asia/Tokyo' ) ) ;
        $this->assertSame( 'UTC' , $a->getTimezone()->getName() ) ;
        $this->assertSame( 'UTC' , $b->getTimezone()->getName() ) ;
    }
}
