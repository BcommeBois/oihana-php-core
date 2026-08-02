<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\addSeconds;

use PHPUnit\Framework\TestCase;

class AddSecondsTest extends TestCase
{
    public function testAddsSeconds() : void
    {
        $result = addSeconds( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 90 ) ;
        $this->assertSame( '2026-01-01 00:01:30' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testSubtractsWithNegativeSeconds() : void
    {
        $result = addSeconds( new DateTimeImmutable( '2026-01-01 00:00:00' ) , -1 ) ;
        $this->assertSame( '2025-12-31 23:59:59' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testZeroSecondsKeepsSameDate() : void
    {
        $result = addSeconds( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 0 ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01 00:00:00' ) ;
        $result = addSeconds( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 00:00:10' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testIsAbsoluteDurationAcrossSpringForwardDst() : void
    {
        $result = addSeconds( new DateTimeImmutable( '2026-03-29 01:30:00' , new \DateTimeZone( 'Europe/Paris' ) ) , 3600 ) ;
        $this->assertSame( '2026-03-29 03:30:00 +02:00' , $result->format( 'Y-m-d H:i:s P' ) ) ;
    }
}
