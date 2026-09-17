<?php

namespace tests\oihana\core\maths;

use function oihana\core\maths\floorValue;

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

    /**
     * A decimal number is almost never exact in memory : `8.2 * 100` holds `819.9999999999999`.
     * Before the noise was ignored, each of these dropped by one hundredth.
     */
    public function testFloorValueIgnoresTheFloatNoise() : void
    {
        $this->assertSame(8.2, floorValue(8.2, 2));
        $this->assertSame(0.29, floorValue(0.29, 2));
        $this->assertSame(1.1, floorValue(0.50 * 2.2, 2));
    }

    public function testFloorValueStillDropsOnARealShortfall() : void
    {
        $this->assertSame(8.19, floorValue(8.199, 2));
        $this->assertSame(0.28, floorValue(0.2899, 2));
    }
}