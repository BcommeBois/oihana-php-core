<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\startOfYear;

use PHPUnit\Framework\TestCase;

class StartOfYearTest extends TestCase
{
    public function testReturnsJanuaryFirstAtMidnight() : void
    {
        $result = startOfYear( new DateTimeImmutable( '2026-07-15 14:30:45.123456' ) ) ;
        $this->assertSame( '2026-01-01 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testIsIdempotentOnJanuaryFirst() : void
    {
        $result = startOfYear( new DateTimeImmutable( '2026-01-01 00:00:00' ) ) ;
        $this->assertSame( '2026-01-01 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = startOfYear( new DateTimeImmutable( '2026-07-15' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-07-15 14:30:45' ) ;
        $result = startOfYear( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-07-15 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
