<?php

namespace oihana\core\maths ;

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
}