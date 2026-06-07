<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\addDays;

use PHPUnit\Framework\TestCase;

class AddDaysTest extends TestCase
{
    public function testAddsDays() : void
    {
        $result = addDays( new DateTimeImmutable( '2026-01-01' ) , 5 ) ;
        $this->assertSame( '2026-01-06' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testSubtractsWithNegativeDays() : void
    {
        $result = addDays( new DateTimeImmutable( '2026-01-01' ) , -1 ) ;
        $this->assertSame( '2025-12-31' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testCrossesMonthBoundary() : void
    {
        $result = addDays( new DateTimeImmutable( '2026-01-30' ) , 3 ) ;
        $this->assertSame( '2026-02-02' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testZeroDaysKeepsSameDate() : void
    {
        $result = addDays( new DateTimeImmutable( '2026-01-01' ) , 0 ) ;
        $this->assertSame( '2026-01-01' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01' ) ;
        $result = addDays( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2026-01-11' , $result->format( 'Y-m-d' ) ) ;
    }
}
