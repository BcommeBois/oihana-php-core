<?php

namespace tests\oihana\core\date;

use function oihana\core\date\durationToSeconds;

use PHPUnit\Framework\TestCase;

class DurationToSecondsTest extends TestCase
{
    public function testNullIsZero() : void
    {
        $this->assertSame( 0.0 , durationToSeconds( null ) ) ;
        $this->assertSame( 0.0 , durationToSeconds() ) ;
    }

    public function testNumericPassthrough() : void
    {
        $this->assertSame( 3725.0 , durationToSeconds( 3725 ) ) ;
        $this->assertSame( 3725.5 , durationToSeconds( 3725.5 ) ) ;
        $this->assertSame( 0.0    , durationToSeconds( 0 ) ) ;
        $this->assertSame( 90.0   , durationToSeconds( '90' ) ) ; // numeric string
    }

    public function testColonMinutesSeconds() : void
    {
        $this->assertSame( 5400.0 , durationToSeconds( '90:00' ) ) ;
        $this->assertSame( 90.5   , durationToSeconds( '1:30.5' ) ) ;
    }

    public function testColonHoursMinutesSeconds() : void
    {
        $this->assertSame( 3725.0 , durationToSeconds( '1:02:05' ) ) ;
        $this->assertSame( 5400.0 , durationToSeconds( '01:30:00' ) ) ;
    }

    public function testColonWithUnexpectedPartCountReturnsZero() : void
    {
        $this->assertSame( 0.0 , durationToSeconds( '1:2:3:4' ) ) ;
    }

    public function testUnitString() : void
    {
        $this->assertSame( 5400.0 , durationToSeconds( '1h 30m' ) ) ;
        $this->assertSame( 4.0    , durationToSeconds( '4s' ) ) ;
        $this->assertSame( 93784.0 , durationToSeconds( '1d 2h 3m 4s' ) ) ;
    }

    public function testUnitStringWithDecimals() : void
    {
        $this->assertSame( 5400.0 , durationToSeconds( '1.5h' ) ) ;
        $this->assertSame( 12.5   , durationToSeconds( '12.5s' ) ) ;
    }

    public function testUnitStringAnyOrder() : void
    {
        $this->assertSame( 3725.0 , durationToSeconds( '5s 2m 1h' ) ) ;
    }

    public function testHoursPerDayAffectsDayUnit() : void
    {
        $this->assertSame( 43200.0 , durationToSeconds( '1.5d' , 8 ) ) ;
        $this->assertSame( 86400.0 , durationToSeconds( '1d' ) ) ;
    }

    public function testUnrecognizedStringIsZero() : void
    {
        $this->assertSame( 0.0 , durationToSeconds( 'hello' ) ) ;
    }
}
