<?php

namespace oihana\core\maths ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CartesianToPolarTest extends TestCase
{
    public function testNormalConversion()
    {
        $result = cartesianToPolar(['x' => 0, 'y' => 5]);
        $this->assertEquals(90, $result['angle']);
        $this->assertEquals(5, $result['radius']);

        $result = cartesianToPolar(['x' => 3, 'y' => 4]);

        $delta = 1e-12;
        $this->assertEqualsWithDelta( 53.13010235415599 , $result['angle'] , $delta , '' );
        $this->assertEquals(5, $result['radius']);
    }

    public function testRadiansOption()
    {
        $result = cartesianToPolar(['x' => 0, 'y' => 1], false);
        $this->assertEquals(M_PI / 2, $result['angle']);
        $this->assertEquals(1, $result['radius']);
    }

    public function testMissingKeysWithoutThrowable()
    {
        $result = cartesianToPolar([]);
        $this->assertEquals(0, $result['angle']);
        $this->assertEquals(0, $result['radius']);

        $result = cartesianToPolar(['x' => 3]);
        $this->assertEquals(0, $result['angle']);
        $this->assertEquals(3, $result['radius']);

        $result = cartesianToPolar(['y' => 4]);
        $this->assertEquals(90, $result['angle']);
        $this->assertEquals(4, $result['radius']);
    }

    public function testMissingKeysWithThrowable()
    {
        $this->expectException(InvalidArgumentException::class);
        cartesianToPolar([], true, true);
    }

    public function testAngleNormalization()
    {
        // Negative y should return angle > 180
        $result = cartesianToPolar(['x' => 1, 'y' => -1]);
        $this->assertEquals(315, $result['angle']);
        $this->assertEquals(sqrt(2), $result['radius']);
    }
}