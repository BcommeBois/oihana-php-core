<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\endOfYear;

use PHPUnit\Framework\TestCase;

class EndOfYearTest extends TestCase
{
    public function testReturnsTheLastMicrosecondOfDecember31st() : void
    {
        $result = endOfYear( new DateTimeImmutable( '2026-07-15' ) ) ;
        $this->assertSame( '2026-12-31 23:59:59.999999' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = endOfYear( new DateTimeImmutable( '2026-07-15' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-07-15 14:30:45' ) ;
        $result = endOfYear( $source ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-07-15 14:30:45' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-12-31 23:59:59' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
