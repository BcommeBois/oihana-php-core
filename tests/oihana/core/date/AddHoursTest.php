<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\addHours;

use PHPUnit\Framework\TestCase;

class AddHoursTest extends TestCase
{
    public function testAddsHours() : void
    {
        $result = addHours( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 5 ) ;
        $this->assertSame( '2026-01-01 05:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testSubtractsWithNegativeHours() : void
    {
        $result = addHours( new DateTimeImmutable( '2026-01-01 00:00:00' ) , -1 ) ;
        $this->assertSame( '2025-12-31 23:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testZeroHoursKeepsSameDate() : void
    {
        $result = addHours( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 0 ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01 00:00:00' ) ;
        $result = addHours( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 10:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testIsAbsoluteDurationAcrossSpringForwardDst() : void
    {
        // Europe/Paris jumps from 02:00 CET to 03:00 CEST on the last Sunday of March.
        $result = addHours( new DateTimeImmutable( '2026-03-29 01:30:00' , new DateTimeZone( 'Europe/Paris' ) ) , 1 ) ;
        $this->assertSame( '2026-03-29 03:30:00 +02:00' , $result->format( 'Y-m-d H:i:s P' ) ) ;
    }
}
