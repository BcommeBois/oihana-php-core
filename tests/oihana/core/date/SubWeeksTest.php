<?php

namespace tests\oihana\core\date;

use DateTime;
use DateTimeImmutable;

use function oihana\core\date\subWeeks;

use PHPUnit\Framework\TestCase;

class SubWeeksTest extends TestCase
{
    public function testSubtractsWeeks() : void
    {
        $result = subWeeks( new DateTimeImmutable( '2026-01-15' ) , 2 ) ;
        $this->assertSame( '2026-01-01' , $result->format( 'Y-m-d' ) ) ;
    }

    public function testReturnsImmutableAndDoesNotMutateSource() : void
    {
        $source = new DateTime( '2026-01-08' ) ;
        $result = subWeeks( $source , 1 ) ;
        $this->assertInstanceOf( DateTimeImmutable::class , $result ) ;
        $this->assertSame( '2026-01-08' , $source->format( 'Y-m-d' ) ) ;
        $this->assertSame( '2026-01-01' , $result->format( 'Y-m-d' ) ) ;
    }
}
