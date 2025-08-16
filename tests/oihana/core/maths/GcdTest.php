<?php

namespace oihana\core\maths ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class GcdTest extends TestCase
{
    public function testPositiveNumbers(): void
    {
        $this->assertSame(6, gcd(48, 18));
        $this->assertSame(1, gcd(17, 31));
        $this->assertSame(12, gcd(36, 60));
    }

    public function testNegativeNumbers(): void
    {
        $this->assertSame(6, gcd(-48, 18));
        $this->assertSame(6, gcd(48, -18));
        $this->assertSame(6, gcd(-48, -18));
    }

    public function testZeroCases(): void
    {
        $this->assertSame(5, gcd(0, 5));
        $this->assertSame(7, gcd(7, 0));
        $this->assertSame(0, gcd(0, 0));
    }

    public function testThrowableZero(): void
    {
        $this->expectException(InvalidArgumentException::class);
        gcd(0, 0, true);
    }

    public function testSameNumbers(): void
    {
        $this->assertSame(7, gcd(7, 7));
        $this->assertSame(15, gcd(15, 15));
    }
}