<?php

namespace tests\oihana\core\date;

use function oihana\core\date\humanizeDuration;

use PHPUnit\Framework\TestCase;

class HumanizeDurationTest extends TestCase
{
    public function testNullAndZeroYieldZeroSeconds() : void
    {
        $this->assertSame( '0s' , humanizeDuration( null ) ) ;
        $this->assertSame( '0s' , humanizeDuration() ) ;
        $this->assertSame( '0s' , humanizeDuration( 0 ) ) ;
        $this->assertSame( '0s' , humanizeDuration( '0s' ) ) ;
    }

    public function testNumericSeconds() : void
    {
        $this->assertSame( '5s'       , humanizeDuration( 5 ) ) ;
        $this->assertSame( '1m'       , humanizeDuration( 60 ) ) ;
        $this->assertSame( '1m 5s'    , humanizeDuration( 65 ) ) ;
        $this->assertSame( '1h 2m 5s' , humanizeDuration( 3725 ) ) ;
        $this->assertSame( '1h'       , humanizeDuration( 3600 ) ) ;
    }

    public function testDaysRollUp() : void
    {
        $this->assertSame( '1d'          , humanizeDuration( 86400 ) ) ;
        $this->assertSame( '1d 2h 3m 4s' , humanizeDuration( 93784 ) ) ;
    }

    public function testFractionalSeconds() : void
    {
        $this->assertSame( '5.5s'    , humanizeDuration( 5.5 ) ) ;
        $this->assertSame( '1m 0.5s' , humanizeDuration( 60.5 ) ) ;
    }

    public function testColonStringNormalizesOverflow() : void
    {
        $this->assertSame( '1h 30m' , humanizeDuration( '90:00' ) ) ;
        $this->assertSame( '1h 2m 5s' , humanizeDuration( '1:02:05' ) ) ;
    }

    public function testUnitString() : void
    {
        $this->assertSame( '1h 30m' , humanizeDuration( '1h 30m' ) ) ;
        $this->assertSame( '1h 30m' , humanizeDuration( '1.5h' ) ) ;
    }

    public function testCustomHoursPerDay() : void
    {
        // With an 8-hour day, 10h rolls into 1d 2h.
        $this->assertSame( '1d 2h' , humanizeDuration( 36000 , 8 ) ) ;
        // '1d' in an 8h day is 8 hours of real time.
        $this->assertSame( '1d' , humanizeDuration( '1d' , 8 ) ) ;
    }

    public function testZeroHoursPerDayDisablesDayComponent() : void
    {
        // No day bucket: everything stays in hours.
        $this->assertSame( '25h' , humanizeDuration( 90000 , 0 ) ) ;
    }
}
