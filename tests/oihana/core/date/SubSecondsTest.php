<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\subSeconds;

use PHPUnit\Framework\TestCase;

class SubSecondsTest extends TestCase
{
    public function testSubtractsSeconds() : void
    {
        $result = subSeconds( new DateTimeImmutable( '2026-01-01 00:01:30' ) , 90 ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-01 00:00:10' ) ;
        $result = subSeconds( $source , 10 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-01 00:00:10' , $source->format( 'Y-m-d H:i:s' ) ) ;
        $this->assertSame( '2026-01-01 00:00:00' , $result->format( 'Y-m-d H:i:s' ) ) ;
    }
}
