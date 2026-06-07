<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;

use function oihana\core\date\isFuture;

use PHPUnit\Framework\TestCase;

class IsFutureTest extends TestCase
{
    private const NOW = '2026-01-01 12:00:00' ;

    public function testFutureDateWithExplicitNow() : void
    {
        $this->assertTrue( isFuture( new DateTimeImmutable( '2030-01-01' ) , new DateTimeImmutable( self::NOW ) ) ) ;
    }

    public function testPastDateWithExplicitNow() : void
    {
        $this->assertFalse( isFuture( new DateTimeImmutable( '2020-01-01' ) , new DateTimeImmutable( self::NOW ) ) ) ;
    }

    public function testEqualInstantIsNotFuture() : void
    {
        $now = new DateTimeImmutable( self::NOW ) ;
        $this->assertFalse( isFuture( $now , $now ) ) ;
    }

    public function testDefaultsToCurrentTime() : void
    {
        $this->assertTrue( isFuture( new DateTimeImmutable( '2999-01-01' ) ) ) ;
        $this->assertFalse( isFuture( new DateTimeImmutable( '2000-01-01' ) ) ) ;
    }
}
