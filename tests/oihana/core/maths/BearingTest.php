<?php

namespace oihana\core\maths ;

use PHPUnit\Framework\TestCase;

class BearingTest extends TestCase
{
    public function testSamePointReturnsZero(): void
    {
        $this->assertSame(
            0.0,
            bearing(37.422045, -122.084347, 37.422045, -122.084347)
        );
    }

    public function testGoogleHQtoSanFrancisco(): void
    {
        $bearing = bearing(37.422045, -122.084347, 37.77493, -122.419416);
        $this->assertEqualsWithDelta(323.1477743368166, $bearing, 1e-9);
    }

    public function testSanFranciscoToGoogleHQInverseBearing(): void
    {
        $bearingForward  = bearing(37.422045, -122.084347, 37.77493, -122.419416);
        $bearingBackward = bearing(37.77493, -122.419416, 37.422045, -122.084347);

        // Environ l'inverse (écart de 180° modulo 360°)
        $this->assertEqualsWithDelta
        (
            fmod($bearingForward + 180.0 , 360.0),
            $bearingBackward,
            0.5
        );
    }

    public function testBearingNorth(): void
    {
        $bearing = bearing(0.0, 0.0, 1.0, 0.0);
        $this->assertEqualsWithDelta(0.0, $bearing, 1e-9);
    }

    public function testBearingEast(): void
    {
        $bearing = bearing(0.0, 0.0, 0.0, 1.0);
        $this->assertEqualsWithDelta(90.0, $bearing, 1e-9);
    }

    public function testBearingSouth(): void
    {
        $bearing = bearing(1.0, 0.0, 0.0, 0.0);
        $this->assertEqualsWithDelta(180.0, $bearing, 1e-9);
    }

    public function testBearingWest(): void
    {
        $bearing = bearing(0.0, 1.0, 0.0, 0.0);
        $this->assertEqualsWithDelta(270.0, $bearing, 1e-9);
    }
}