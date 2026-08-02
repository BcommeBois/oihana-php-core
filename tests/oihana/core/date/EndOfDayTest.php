<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\endOfDay;

use PHPUnit\Framework\TestCase;

class EndOfDayTest extends TestCase
{
    public function testReturnsTheLastMicrosecondOfTheSameDay() : void
    {
        $result = endOfDay( new DateTimeImmutable( '2026-03-15 14:30:45.123456' ) ) ;
        $this->assertSame( '2026-03-15 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = endOfDay( new DateTimeImmutable( '2026-03-15 14:30:45' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-03-15 14:30:45' ) ;
        $result = endOfDay( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-03-15 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-03-15 23:59:59' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
