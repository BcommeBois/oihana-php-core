<?php

namespace tests\oihana\core\date;

use oihana\core\date\DurationUnit;

use function oihana\core\date\durationParts;

use PHPUnit\Framework\TestCase;

class DurationPartsTest extends TestCase
{
    /**
     * @return array{d:int,h:int,m:int,s:int|float}
     */
    private function parts( int $days , int $hours , int $minutes , int|float $seconds ) : array
    {
        return
        [
            DurationUnit::DAY    => $days ,
            DurationUnit::HOUR   => $hours ,
            DurationUnit::MINUTE => $minutes ,
            DurationUnit::SECOND => $seconds ,
        ] ;
    }

    public function testNullAndZeroGiveNothing() : void
    {
        $this->assertSame( $this->parts( 0 , 0 , 0 , 0 ) , durationParts() ) ;
        $this->assertSame( $this->parts( 0 , 0 , 0 , 0 ) , durationParts( null ) ) ;
        $this->assertSame( $this->parts( 0 , 0 , 0 , 0 ) , durationParts( 0 ) ) ;
    }

    public function testNumericSecondsAreBrokenDown() : void
    {
        $this->assertSame( $this->parts( 0 , 1 , 2 , 5 ) , durationParts( 3725 ) ) ;
        $this->assertSame( $this->parts( 1 , 2 , 3 , 4 ) , durationParts( 93784 ) ) ;
    }

    public function testOnlyTheSecondsKeepAFraction() : void
    {
        $this->assertSame( $this->parts( 0 , 0 , 0 , 5.5 ) , durationParts( 5.5 ) ) ;
        $this->assertSame( $this->parts( 0 , 0 , 1 , 0.5 ) , durationParts( 60.5 ) ) ;
    }

    /**
     * Every component stays within its range, whatever the input form.
     */
    public function testAnyInputFormIsNormalized() : void
    {
        $this->assertSame( $this->parts( 0 , 1 , 30 , 0 ) , durationParts( '90:00' ) ) ;
        $this->assertSame( $this->parts( 0 , 1 , 30 , 0 ) , durationParts( '1.5h' ) ) ;
        $this->assertSame( $this->parts( 1 , 0 , 1 , 0 ) , durationParts( '1d 1m' ) ) ;
    }

    public function testTheHoursPerDayDecideTheDays() : void
    {
        $this->assertSame( $this->parts( 1 , 2 , 0 , 0 ) , durationParts( '10h' , 8 ) ) ;
    }

    public function testNoHoursPerDayKeepsEveryHourInTheHours() : void
    {
        $this->assertSame( $this->parts( 0 , 26 , 0 , 0 ) , durationParts( 93600 , 0 ) ) ;
    }

    public function testThePartsAreKeyedByTheUnitSuffixes() : void
    {
        $this->assertSame( DurationUnit::all() , array_keys( durationParts( 1 ) ) ) ;
    }
}
