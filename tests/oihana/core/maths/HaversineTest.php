<?php

namespace oihana\core\maths ;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class HaversineTest extends TestCase
{
    public function testSamePointReturnsZero(): void
    {
        $lat = 37.422045;
        $lng = -122.084347;

        $this->assertSame(0.0, haversine( $lat , $lng , $lat , $lng ) );
    }

    public function testGoogleHqtToSanFrancisco(): void
    {
        $googleHQ = [37.422045, -122.084347];
        $sanFrancisco = [37.77493, -122.419416];

        $distance = haversine(
            $googleHQ[0], $googleHQ[1],
            $sanFrancisco[0], $sanFrancisco[1]
        );

        // Expected ~49_103 meters, allow tolerance of 100 meters
        $this->assertEqualsWithDelta(49103.007, $distance, 100.0);
    }

    public function testSymmetry(): void
    {
        $point1 = [ 48.8566 , 2.3522   ] ; // Paris
        $point2 = [ 40.7128 , -74.0060 ] ; // New York

        $d1 = haversine($point1[0], $point1[1], $point2[0], $point2[1]);
        $d2 = haversine($point2[0], $point2[1], $point1[0], $point1[1]);

        $this->assertSame($d1, $d2);
    }

    public function testCustomRadiusWithoutPrecision(): void
    {
        $p1 = [0.0, 0.0];
        $p2 = [0.0, 90.0];

        $distance = haversine($p1[0], $p1[1], $p2[0], $p2[1], 1.0);

        $this->assertEqualsWithDelta(M_PI / 2, $distance, 1e-12);
    }

    public function testCustomRadiusWithPrecision(): void
    {
        $p1 = [0.0, 0.0];
        $p2 = [0.0, 90.0];

        // With precision=3, expect ~1.571
        $distance = haversine($p1[0], $p1[1], $p2[0], $p2[1], 1.0, 3);

        $this->assertSame(1.571, $distance);
    }

    public function testInvalidPrecisionThrowsException(): void
    {
        $this->expectException( InvalidArgumentException::class ) ;
        haversine(0.0, 0.0, 1.0, 1.0, 6371000, -1);
    }
}