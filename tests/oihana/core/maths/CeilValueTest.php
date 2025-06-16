<?php

namespace oihana\core\maths ;

use PHPUnit\Framework\TestCase;

class CeilValueTest extends TestCase
{
    public function testCeilValueWithPositiveNumbers()
    {
        $this->assertEquals(3.15, ceilValue(3.14159, 2));
        $this->assertEquals(4.0, ceilValue(3.14159, 0));
        $this->assertEquals(3.2, ceilValue(3.14159, 1));
    }

    public function testCeilValueWithNegativeNumbers()
    {
        $this->assertEquals(-3.14, ceilValue(-3.14159, 2));
        $this->assertEquals(-3.0, ceilValue(-3.14159, 0));
        $this->assertEquals(-3.1, ceilValue(-3.14159, 1));
    }

    public function testCeilValueWithZeroFloatCount()
    {
        $this->assertEquals(4.0, ceilValue(3.2, 0));
        $this->assertEquals(-3.0, ceilValue(-3.2, 0));
        $this->assertEquals(5.0, ceilValue(5.0, 0));
    }

    public function testCeilValueWithNegativeFloatCount()
    {
        $this->assertEquals(4.0, ceilValue(3.2, -1));
        $this->assertEquals(-3.0, ceilValue(-3.2, -1));
        $this->assertEquals(5.0, ceilValue(5.0, -1));
    }

    public function testCeilValueWithIntegerValues()
    {
        $this->assertEquals(5.0, ceilValue(5, 0));
        $this->assertEquals(5.0, ceilValue(5, 2));
        $this->assertEquals(5.0, ceilValue(5.0, 0));
    }

    public function testCeilValueWithHighPrecision()
    {
        $this->assertEquals(3.1416, ceilValue(3.14159, 4));
        $this->assertEquals(-3.1415 , ceilValue(-3.14159265, 4));
    }

    public function testCeilValueWithEdgeCases()
    {
        $this->assertEquals(1.0, ceilValue(0.999, 2));
        $this->assertEquals(0.0, ceilValue(0.0, 0));
        $this->assertEquals(1.0, ceilValue(0.9999, 3));
    }
}