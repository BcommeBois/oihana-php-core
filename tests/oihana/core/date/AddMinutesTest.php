<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\addMinutes;

use PHPUnit\Framework\TestCase;

class AddMinutesTest extends TestCase
{
    public function testAddsMinutes() : void
    {
        $result = addMinutes( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 90 ) ;
        $this->assertSame( '2026-01-01 01:30:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testSubtractsWithNegativeMinutes() : void
    {
        $result = addMinutes( new DateTimeImmutable( '2026-01-01 00:00:00' ) , -1 ) ;
        $this->assertSame( '2025-12-31 23:59:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testZeroMinutesKeepsSameDate() : void
    {
        $result = addMinutes( new DateTimeImmutable( '2026-01-01 00:00:00' ) , 0 ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01 00:00:00' ) ;
        $result = addMinutes( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 00:10:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
