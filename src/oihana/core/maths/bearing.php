<?php

namespace oihana\core\maths ;

/**
 * Calculates the initial bearing (sometimes referred to as forward azimuth)
 * which if followed in a straight line along a great-circle arc will take
 * you from the start point to the end point (in degrees).
 *
 * @param float $latitude1  The first latitude coordinate in degrees.
 * @param float $longitude1 The first longitude coordinate in degrees.
 * @param float $latitude2  The second latitude coordinate in degrees.
 * @param float $longitude2 The second longitude coordinate in degrees.
 *
 * @return float The bearing in degrees from North.
 *
 * @example
 * ```php
 * $position1 = [ 'x' => 37.422045 , 'y' => -122.084347 ]; // Google HQ
 * $position2 = [ 'x' => 37.77493  , 'y' => -122.419416 ]; // San Francisco, CA
 *
 * echo bearing($position1['x'], $position1['y'], $position2['x'], $position2['y']);
 * // 323.1477743368166
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function bearing
(
    float $latitude1  ,
    float $longitude1 ,
    float $latitude2  ,
    float $longitude2
)
: float
{
    $deg2rad = M_PI / 180.0 ;
    $rad2deg = 180.0 / M_PI ;

    $lat1 = $latitude1 * $deg2rad ;
    $lat2 = $latitude2 * $deg2rad ;
    $dLng = ($longitude2 - $longitude1) * $deg2rad ;

    $y = sin( $dLng ) * cos( $lat2 ) ;
    $x = cos( $lat1 ) * sin( $lat2 ) - sin( $lat1 ) * cos( $lat2 ) * cos( $dLng ) ;

    return fmod(( atan2( $y , $x ) * $rad2deg + 360.0 ) , 360.0 ) ;
}