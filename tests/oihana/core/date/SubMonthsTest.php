<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\subMonths;

use PHPUnit\Framework\TestCase;

class SubMonthsTest extends TestCase
{
    public function testSubtractsMonths() : void
    {
        $result = subMonths( new DateTimeImmutable( '2026-03-15' ) , 1 ) ;
        $this->assertSame( '2026-02-15' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testClampsDayOverflowToLastDayOfTargetMonth() : void
    {
        $result = subMonths( new DateTimeImmutable( '2026-03-31' ) , 1 ) ;
        $this->assertSame( '2026-02-28' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-03-15' ) ;
        $result = subMonths( $source , 1 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-03-15' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2026-02-15' , $result->format( 'Y-m-d' ) ) ;
    }
}
