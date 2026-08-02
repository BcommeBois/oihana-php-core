<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\startOfDay;

use PHPUnit\Framework\TestCase;

class StartOfDayTest extends TestCase
{
    public function testReturnsMidnightOfTheSameDay() : void
    {
        $result = startOfDay( new DateTimeImmutable( '2026-03-15 14:30:45.123456' ) ) ;
        $this->assertSame( '2026-03-15 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testIsIdempotentOnAnAlreadyMidnightDate() : void
    {
        $result = startOfDay( new DateTimeImmutable( '2026-03-15 00:00:00' ) ) ;
        $this->assertSame( '2026-03-15 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = startOfDay( new DateTimeImmutable( '2026-03-15 14:30:45' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-03-15 14:30:45' ) ;
        $result = startOfDay( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-03-15 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-03-15 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
