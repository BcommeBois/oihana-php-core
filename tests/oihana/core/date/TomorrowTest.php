<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\tomorrow;

use PHPUnit\Framework\TestCase;

class TomorrowTest extends TestCase
{
    public function testReturnsMidnightOfTheNextDay() : void
    {
        $result = tomorrow( new DateTimeImmutable( '2026-03-15 14:30:45' ) ) ;
        $this->assertSame( '2026-03-16 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testCrossesAMonthBoundary() : void
    {
        $result = tomorrow( new DateTimeImmutable( '2026-01-31 23:59:59' ) ) ;
        $this->assertSame( '2026-02-01 00:00:00.000000' , $result->format( 'Y-m-d H:i:s.u' ) ) ;
    }

    public function testPreservesTheSourceTimezone() : void
    {
        $result = tomorrow( new DateTimeImmutable( '2026-03-15' , new DateTimeZone( 'Europe/Paris' ) ) ) ;
        $this->assertSame( 'Europe/Paris' , $result->getTimezone()->getName() ) ;
    }

    public function testDefaultsToTheCurrentDateTime() : void
    {
        $result = tomorrow() ;
        $expected = ( new DateTimeImmutable( '+1 day' ) )->format( 'Y-m-d' ) ;
        $this->assertSame( $expected , $result->format( 'Y-m-d' ) ) ;
    }
}
