<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\subHours;

use PHPUnit\Framework\TestCase;

class SubHoursTest extends TestCase
{
    public function testSubtractsHours() : void
    {
        $result = subHours( new DateTimeImmutable( '2026-01-01 05:00:00' ) , 5 ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01 10:00:00' ) ;
        $result = subHours( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01 10:00:00' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
