<?php

namespace oihana\core\maths ;

use PHPUnit\Framework\TestCase;

class FloorValueTest extends TestCase
{
    public function testFloorValueWithPositiveNumbers()
    {
        $this->assertEquals(3.14, floorValue(3.14159, 2));
        $this->assertEquals(3.0, floorValue(3.14159, 0));
        $this->assertEquals(3.1, floorValue(3.14159, 1));
    }

    public function testFloorValueWithNegativeNumbers()
    {
        $this->assertEquals(-3.15, floorValue(-3.14159, 2));
        $this->assertEquals(-4.0, floorValue(-3.14159, 0));
        $this->assertEquals(-3.2, floorValue(-3.14159, 1));
    }

    public function testFloorValueWithZeroFloatCount()
    {
        $this->assertEquals(3.0, floorValue(3.2, 0));
        $this->assertEquals(-4.0, floorValue(-3.2, 0));
        $this->assertEquals(5.0, floorValue(5.0, 0));
    }

    public function testFloorValueWithNegativeFloatCount()
    {
        $this->assertEquals(3.0, floorValue(3.2, -1));
        $this->assertEquals(-4.0, floorValue(-3.2, -1));
        $this->assertEquals(5.0, floorValue(5.0, -1));
    }

    public function testFloorValueWithIntegerValues()
    {
        $this->assertEquals(5.0, floorValue(5, 0));
        $this->assertEquals(5.0, floorValue(5, 2));
        $this->assertEquals(5.0, floorValue(5.0, 0));
    }

    public function testFloorValueWithHighPrecision()
    {
        $this->assertEquals(3.1415, floorValue(3.14159, 4));
        $this->assertEquals(-3.1416, floorValue(-3.14159265, 4));
    }

    public function testFloorValueWithEdgeCases()
    {
        $this->assertEquals(0.99, floorValue(0.999, 2));
        $this->assertEquals(0.0, floorValue(0.0, 0));
        $this->assertEquals(0.999, floorValue(0.9999, 3));
    }
}