<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\subYears;

use PHPUnit\Framework\TestCase;

class SubYearsTest extends TestCase
{
    public function testSubtractsYears() : void
    {
        $result = subYears( new DateTimeImmutable( '2026-01-15' ) , 1 ) ;
        $this->assertSame( '2025-01-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testClampsALeapDayOntoANonLeapYear() : void
    {
        $result = subYears( new DateTimeImmutable( '2028-02-29' ) , 1 ) ;
        $this->assertSame( '2027-02-28' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-15' ) ;
        $result = subYears( $source , 1 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-15' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2025-01-15' , $result->format( 'Y-m-d' ) ) ;
    }
}
