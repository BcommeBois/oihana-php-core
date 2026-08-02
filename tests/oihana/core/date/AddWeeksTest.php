<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\addWeeks;

use PHPUnit\Framework\TestCase;

class AddWeeksTest extends TestCase
{
    public function testAddsWeeks() : void
    {
        $result = addWeeks( new DateTimeImmutable( '2026-01-01' ) , 2 ) ;
        $this->assertSame( '2026-01-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testSubtractsWithNegativeWeeks() : void
    {
        $result = addWeeks( new DateTimeImmutable( '2026-01-01' ) , -1 ) ;
        $this->assertSame( '2025-12-25' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testZeroWeeksKeepsSameDate() : void
    {
        $result = addWeeks( new DateTimeImmutable( '2026-01-01' ) , 0 ) ;
        $this->assertSame( '2026-01-01' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01' ) ;
        $result = addWeeks( $source , 1 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2026-01-08' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testIsCalendarArithmeticPreservingWallClock() : void
    {
        // 2026-03-29 is the spring-forward day in Europe/Paris, but 01:30 is before
        // the 02:00 -> 03:00 gap, so the wall-clock time and the +01:00 offset both hold.
        $result = addWeeks( new DateTimeImmutable( '2026-03-22 01:30:00' , new DateTimeZone( 'Europe/Paris' ) ) , 1 ) ;
        $this->assertSame( '2026-03-29 01:30:00 +01:00' , $result->format( 'Y-m-d H:i:s P' ) ) ;
    }
}
