<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\endOfMonth;

use PHPUnit\Framework\TestCase;

class EndOfMonthTest extends TestCase
{
    public function testReturnsTheLastMicrosecondOfA31DayMonth() : void
    {
        $result = endOfMonth( new DateTimeImmutable( '2026-03-15' ) ) ;
        $this->assertSame( '2026-03-31 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testReturnsTheLastMicrosecondOfA30DayMonth() : void
    {
        $result = endOfMonth( new DateTimeImmutable( '2026-04-05' ) ) ;
        $this->assertSame( '2026-04-30 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testHandlesFebruaryInANonLeapYear() : void
    {
        $result = endOfMonth( new DateTimeImmutable( '2026-02-05' ) ) ;
        $this->assertSame( '2026-02-28 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testHandlesFebruaryInALeapYear() : void
    {
        $result = endOfMonth( new DateTimeImmutable( '2024-02-05' ) ) ;
        $this->assertSame( '2024-02-29 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = endOfMonth( new DateTimeImmutable( '2026-03-15' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-03-15 14:30:45' ) ;
        $result = endOfMonth( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-03-15 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-03-31 23:59:59' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
