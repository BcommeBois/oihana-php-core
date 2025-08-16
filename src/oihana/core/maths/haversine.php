<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Calculate the great-circle distance between two points on a sphere using the Haversine formula.
 * This is commonly used for distances on Earth. It's faster than the Vincenty formula but less precise.
 *
 * @param float     $latitude1  The latitude of the first point in degrees.
 * @param float     $longitude1 The longitude of the first point in degrees.
 * @param float     $latitude2  The latitude of the second point in degrees.
 * @param float     $longitude2 The longitude of the second point in degrees.
 * @param float     $radius     The radius of the sphere (default is Earth's mean radius: 6,371,000 meters).
 * @param int|null  $precision  Number of decimal places to round to. If null, returns full precision.
 *
 * @return float The distance between the two points in meters.
 *
 * @example
 * ```php
 * use function oihana\core\maths\haversine;
 *
 * $pos1 = [ 37.422045, -122.084347 ] ; // Google HQ
 * $pos2 = [ 37.77493,  -122.419416 ] ; // San Francisco, CA
 *
 * echo haversine( $pos1[0] , $pos1[1], $pos2[0] , $pos2[1] ) ;       // Full precision
 * echo haversine( $pos1[0] , $pos1[1], $pos2[0] , $pos2[1] , 6371000, 3 ); // Rounded to 3 decimals
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function haversine
(
    float $latitude1  ,
    float $longitude1 ,
    float $latitude2  ,
    float $longitude2 ,
    float $radius     = 6371000 ,
    ?int  $precision  = null
)
: float
{
    if ( $precision !== null && $precision < 0 )
    {
        throw new InvalidArgumentException("Precision must be >= 0 or null." ) ;
    }

    $dLat = deg2rad($latitude2  - $latitude1  ) ;
    $dLng = deg2rad($longitude2 - $longitude1 ) ;

    $a = sin($dLat / 2 ) ** 2
        + cos( deg2rad( $latitude1 ) ) * cos(deg2rad( $latitude2 ) )
        * sin($dLng / 2 ) ** 2 ;

    $distance = 2 * atan2(sqrt($a), sqrt(1 - $a)) * $radius;

    if ( !is_finite( $distance ) )
    {
        return 0.0 ;
    }

    return $precision === null ? $distance : round( $distance , $precision ) ;
}