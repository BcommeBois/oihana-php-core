<?php

namespace tests\oihana\core\date;

use DateTimeImmutable;

use function oihana\core\date\isWeekend;

use PHPUnit\Framework\TestCase;

class IsWeekendTest extends TestCase
{
    public function testSaturdayIsWeekend() : void
    {
        $this->assertTrue( isWeekend( new DateTimeImmutable( '2024-01-06' ) ) ) ;
    }

    public function testSundayIsWeekend() : void
    {
        $this->assertTrue( isWeekend( new DateTimeImmutable( '2024-01-07' ) ) ) ;
    }

    public function testMondayIsNotWeekend() : void
    {
        $this->assertFalse( isWeekend( new DateTimeImmutable( '2024-01-08' ) ) ) ;
    }

    public function testFridayIsNotWeekend() : void
    {
        $this->assertFalse( isWeekend( new DateTimeImmutable( '2024-01-05' ) ) ) ;
    }
}
