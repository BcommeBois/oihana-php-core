<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;
use DateTimeZone;

use function oihana\core\date\isToday;

use PHPUnit\Framework\TestCase;

class IsTodayTest extends TestCase
{
    public function testTrueForTheCurrentDateTimeWithNoNowOverride() : void
    {
        $this->assertTrue( isToday( new DateTimeImmutable() ) ) ;
    }

    public function testFalseForAPastDateWithNoNowOverride() : void
    {
        $this->assertFalse( isToday( new DateTimeImmutable( '2000-01-01' ) ) ) ;
    }

    public function testTrueWhenCompareToAnExplicitNow() : void
    {
        $date = new DateTimeImmutable( '2026-03-15 08:00:00' ) ;
        $now  = new DateTimeImmutable( '2026-03-15 23:00:00' ) ;
        $this->assertTrue( isToday( $date , $now ) ) ;
    }

    public function testFalseWhenTheExplicitNowIsADifferentDay() : void
    {
        $date = new DateTimeImmutable( '2026-03-15' ) ;
        $now  = new DateTimeImmutable( '2026-03-16' ) ;
        $this->assertFalse( isToday( $date , $now ) ) ;
    }

    public function testAcceptsAnExplicitTimezone() : void
    {
        $date = new DateTimeImmutable( '2026-01-01 01:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $now  = new DateTimeImmutable( '2026-01-01 20:00:00' , new DateTimeZone( 'UTC' ) ) ;
        $this->assertTrue ( isToday( $date , $now ) ) ;
        $this->assertFalse( isToday( $date , $now , new DateTimeZone( 'Asia/Tokyo' ) ) ) ;
    }
}
