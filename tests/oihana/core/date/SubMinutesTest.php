<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\subMinutes;

use PHPUnit\Framework\TestCase;

class SubMinutesTest extends TestCase
{
    public function testSubtractsMinutes() : void
    {
        $result = subMinutes( new DateTimeImmutable( '2026-01-01 01:30:00' ) , 90 ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01 00:10:00' ) ;
        $result = subMinutes( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01 00:10:00' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
