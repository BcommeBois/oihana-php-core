<?php

namespace tests\oihana\core\maths;

use function oihana\core\maths\roundValue;

use PHPUnit\Framework\TestCase;

class RoundValueTest extends TestCase
{
    public function testRoundValueWithPositiveNumbers()
    {
        $this->assertEquals(3.14, roundValue(3.14159, 2));
        $this->assertEquals(3.0, roundValue(3.14159));
        $this->assertEquals(3.1, roundValue(3.14159, 1));
    }

    public function testRoundValueWithNegativeNumbers()
    {
        $this->assertEquals(-3.14, roundValue(-3.14159, 2));
        $this->assertEquals(-3.0, roundValue(-3.14159));
        $this->assertEquals(-3.1, roundValue(-3.14159, 1));
    }

    public function testRoundValueWithZeroFloatCount()
    {
        $this->assertEquals(3.0, roundValue(3.2));
        $this->assertEquals(-3.0, roundValue(-3.2));
        $this->assertEquals(5.0, roundValue(5.0));
        $this->assertEquals(4.0, roundValue(3.5));
        $this->assertEquals(4.0, roundValue(3.6));
        $this->assertEquals(3.0, roundValue(3.4));
    }

    public function testRoundValueWithNegativeFloatCount()
    {
        $this->assertEquals(3.0, roundValue(3.2, -1));
        $this->assertEquals(-3.0, roundValue(-3.2, -1));
        $this->assertEquals(5.0, roundValue(5.0, -1));
    }

    public function testRoundValueWithIntegerValues()
    {
        $this->assertEquals(5.0, roundValue(5));
        $this->assertEquals(5.0, roundValue(5, 2));
        $this->assertEquals(5.0, roundValue(5.0));
    }

    public function testRoundValueWithHighPrecision()
    {
        $this->assertEquals(3.1416, roundValue(3.14159, 4));
        $this->assertEquals(-3.1416, roundValue(-3.14159265, 4));
    }

    public function testRoundValueWithEdgeCases()
    {
        $this->assertEquals(1.0, roundValue(0.999, 2));
        $this->assertEquals(0.0, roundValue(0.0));
        $this->assertEquals(1.0, roundValue(0.9999, 3));
        $this->assertEquals(2.0, roundValue(1.5));
        $this->assertEquals(3.0, roundValue(2.5));
        $this->assertEquals(-2.0, roundValue(-1.5));
        $this->assertEquals(-3.0, roundValue(-2.5));
    }

    /**
     * A decimal number is almost never exact in memory : `0.235 * 2.5 * 1000` holds
     * `587.4999999999999`, which used to round down, away from the exact arithmetic.
     */
    public function testRoundValueIgnoresTheFloatNoise() : void
    {
        $this->assertSame(0.588, roundValue(0.235 * 2.5, 3));
        $this->assertSame(1.1, roundValue(0.50 * 2.2, 2));
        $this->assertSame(0.29, roundValue(0.29, 2));
    }

    public function testRoundValueStillRoundsAHalfWayValueUp() : void
    {
        $this->assertSame(0.588, roundValue(0.5875, 3));
        $this->assertSame(0.587, roundValue(0.58749, 3));
    }
}