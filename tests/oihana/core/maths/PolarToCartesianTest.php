<?php

namespace oihana\core\maths ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PolarToCartesianTest extends TestCase
{
    public function testNormalConversion(): void
    {
        $polar = ['angle' => 45, 'radius' => 10];
        $cartesian = polarToCartesian($polar);

        $this->assertEqualsWithDelta(7.0710678118655, $cartesian['x'], 0.00001);
        $this->assertEqualsWithDelta(7.0710678118655, $cartesian['y'], 0.00001);
    }

    public function testMissingKeysWithoutThrowable(): void
    {
        $polar = ['radius' => 5]; // 'angle' missing
        $cartesian = polarToCartesian($polar);

        $this->assertEquals(5, $cartesian['x']); // cos(0)=1
        $this->assertEquals(0, $cartesian['y']); // sin(0)=0
    }

    public function testMissingKeysWithThrowable(): void
    {
        $this->expectException( InvalidArgumentException::class);
        $polar = ['radius' => 5];
        polarToCartesian($polar, true, true);
    }

    public function testRadians(): void
    {
        $polar = ['angle' => M_PI / 4, 'radius' => 10];
        $cartesian = polarToCartesian($polar, false); // angle en radians

        $this->assertEqualsWithDelta(7.0710678118655, $cartesian['x'], 0.00001);
        $this->assertEqualsWithDelta(7.0710678118655, $cartesian['y'], 0.00001);
    }
}