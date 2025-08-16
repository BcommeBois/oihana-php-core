<?php

namespace oihana\core\maths ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class FixAngleTest extends TestCase
{
    public function testZeroReturnsZero(): void
    {
        $this->assertSame(0.0, fixAngle(0));
    }

    public function testAngleWithinRangeUnchanged(): void
    {
        $this->assertSame(45.0, fixAngle(45));
        $this->assertSame(359.5, fixAngle(359.5));
    }

    public function testPositiveOverflow(): void
    {
        $this->assertSame(10.0, fixAngle(370));
        $this->assertSame(0.5, fixAngle(720.5));
    }

    public function testNegativeAngles(): void
    {
        $this->assertSame(270.0, fixAngle(-90));
        $this->assertSame(350.0, fixAngle(-10));
    }

    public function testLargeNegativeAngles(): void
    {
        $this->assertSame(0.0, fixAngle(-720));
        $this->assertSame(5.0, fixAngle(-715));
    }

    public function testNanReturnsZero(): void
    {
        $this->assertSame(0.0, fixAngle(NAN));
    }
}