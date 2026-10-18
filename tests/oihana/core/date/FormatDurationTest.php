<?php

namespace tests\oihana\core\date;

use function oihana\core\date\formatDuration;

use PHPUnit\Framework\TestCase;

class FormatDurationTest extends TestCase
{
    public function testNullAndZero() : void
    {
        $this->assertSame( '0' , formatDuration() ) ;
        $this->assertSame( '0' , formatDuration( null ) ) ;
        $this->assertSame( '0' , formatDuration( 0 ) ) ;
        $this->assertSame( '0:00:00' , formatDuration( 0 , true ) ) ;
    }

    public function testSecondsAlone() : void
    {
        $this->assertSame( '28'      , formatDuration( 28 ) ) ;
        $this->assertSame( '5'       , formatDuration( 5 ) ) ;
        $this->assertSame( '0:00:28' , formatDuration( 28 , true ) ) ;
        $this->assertSame( '0:00:05' , formatDuration( 5 , true ) ) ;
    }

    public function testMinutesAndSeconds() : void
    {
        $this->assertSame( '1:42'    , formatDuration( 102 ) ) ;
        $this->assertSame( '1:05'    , formatDuration( 65 ) ) ;
        $this->assertSame( '42:00'   , formatDuration( 2520 ) ) ;
        $this->assertSame( '0:01:42' , formatDuration( 102 , true ) ) ;
        $this->assertSame( '0:42:00' , formatDuration( 2520 , true ) ) ;
    }

    public function testHoursMinutesAndSeconds() : void
    {
        $this->assertSame( '1:42:00' , formatDuration( 6120 ) ) ;
        $this->assertSame( '1:00:00' , formatDuration( 3600 ) ) ;
        $this->assertSame( '1:02:05' , formatDuration( 3725 ) ) ;
        $this->assertSame( '1:42:00' , formatDuration( 6120 , true ) ) ;
    }

    public function testFractionalSeconds() : void
    {
        $this->assertSame( '5.5'       , formatDuration( 5.5 ) ) ;
        $this->assertSame( '1:05.5'    , formatDuration( 65.5 ) ) ;
        $this->assertSame( '0:00:05.5' , formatDuration( 5.5 , true ) ) ;
        $this->assertSame( '0:01:05.5' , formatDuration( 65.5 , true ) ) ;
    }

    /**
     * The days are folded into the hours.
     */
    public function testDaysAreFoldedIntoTheHours() : void
    {
        $this->assertSame( '25:00:00' , formatDuration( 90000 ) ) ;
        $this->assertSame( '10:00:00' , formatDuration( '10h' , false , 8 ) ) ;
        $this->assertSame( '26:00:00' , formatDuration( 93600 , false , 0 ) ) ;
    }

    /**
     * An improvement on the colon formatter of the former TimeInterval, which wrote "90:00" back
     * as it was read : minutes never exceed 59.
     */
    public function testAColonStringIsNormalized() : void
    {
        $this->assertSame( '1:30:00' , formatDuration( '90:00' ) ) ;
        $this->assertSame( '1:30:00' , formatDuration( '90:00' , true ) ) ;
    }

    public function testAUnitStringIsRead() : void
    {
        $this->assertSame( '1:42:00' , formatDuration( '1h 42m' ) ) ;
    }
}
