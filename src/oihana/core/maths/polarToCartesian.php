<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Converts a polar coordinate to a cartesian vector.
 *
 * @param array{angle: float, radius: float} $vector  Polar coordinates with keys 'angle' and 'radius'.
 * @param bool                               $degrees Whether the angle is in degrees (default: true).
 *
 * @return array{x: float, y: float} Cartesian representation with keys 'x' and 'y'.
 *
 * @throws InvalidArgumentException If $throwable is true and keys are missing.
 *
 * @example
 * ```php
 * use function oihana\core\maths\polarToCartesian;
 *
 * // Example 1: normal usage
 * $polar = ['angle' => 45, 'radius' => 10];
 * $cartesian = polarToCartesian($polar) ;
 * print_r($cartesian);
 * // Output: ['x' => 7.0710678118655, 'y' => 7.0710678118655]
 *
 * // Example 2: missing angle, throwable = false (default)
 * $polar2 = ['radius' => 5];
 * $cartesian2 = polarToCartesian($polar2) ;
 * print_r($cartesian2);
 * // Output: ['x' => 0, 'y' => 0]
 *
 * // Example 3: missing angle, throwable = true
 * $polar3 = ['radius' => 5];
 * try
 * {
 *     $cartesian3 = polarToCartesian($polar3, true, true);
 * }
 * catch ( InvalidArgumentException $e )
 * {
 *     echo $e->getMessage();
 * }
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function polarToCartesian( array $vector , bool $degrees = true , bool $throwable = false ): array
{
    if ( $throwable && ( !isset( $vector['angle'] ) || !isset( $vector['radius'] ) ) )
    {
        throw new InvalidArgumentException("Polar vector must have 'angle' and 'radius' keys.");
    }

    $angle  = $vector[ 'angle'  ] ?? 0 ;
    $radius = $vector[ 'radius' ] ?? 0 ;

    if ( $degrees )
    {
        $angle *= deg2rad(1 ) ; // DEG2RAD = π/180
    }

    return
    [
        'x' => $radius * cos( $angle ) ,
        'y' => $radius * sin( $angle ) ,
    ];
}