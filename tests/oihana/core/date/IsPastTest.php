<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;

use function oihana\core\date\isPast;

use PHPUnit\Framework\TestCase;

class IsPastTest extends TestCase
{
    private const NOW = '2026-01-01 12:00:00' ;

    public function testPastDateWithExplicitNow() : void
    {
        $this->assertTrue( isPast( new DateTimeImmutable( '2020-01-01' ) , new DateTimeImmutable( self::NOW ) ) ) ;
    }

    public function testFutureDateWithExplicitNow() : void
    {
        $this->assertFalse( isPast( new DateTimeImmutable( '2030-01-01' ) , new DateTimeImmutable( self::NOW ) ) ) ;
    }

    public function testEqualInstantIsNotPast() : void
    {
        $now = new DateTimeImmutable( self::NOW ) ;
        $this->assertFalse( isPast( $now , $now ) ) ;
    }

    public function testDefaultsToCurrentTime() : void
    {
        $this->assertTrue( isPast( new DateTimeImmutable( '2000-01-01' ) ) ) ;
        $this->assertFalse( isPast( new DateTimeImmutable( '2999-01-01' ) ) ) ;
    }
}
