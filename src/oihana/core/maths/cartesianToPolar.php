<?php

namespace oihana\core\maths ;

use InvalidArgumentException;

/**
 * Converts a cartesian vector to polar coordinates.
 *
 * @param array{ x?: float|int, y?: float|int} $vector    Cartesian coordinates with keys 'x' and 'y'.
 * @param bool                                 $degrees   Whether the returned angle should be in degrees (default: true).
 * @param bool                                 $throwable Whether to throw an exception if keys are missing (default: false).
 *
 * @return array{ angle :float, radius :float} Polar coordinates with keys 'angle' and 'radius'.
 *
 * @throws InvalidArgumentException if $throwable is true and required keys are missing.
 *
 * @example
 * ```php
 * $polar = cartesianToPolar(['x' => 0, 'y' => 5]);
 * // Returns: ['angle' => 90, 'radius' => 5]
 *
 * $polar = cartesianToPolar(['x' => 1, 'y' => 1], false);
 * // Returns angle in radians
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function cartesianToPolar( array $vector, bool $degrees = true , bool $throwable = false): array
{
    if ( !isset( $vector['x'] , $vector['y' ] ) )
    {
        if ( $throwable )
        {
            throw new InvalidArgumentException("Missing 'x' or 'y' key in vector array");
        }
        $x = $vector['x'] ?? 0 ;
        $y = $vector['y'] ?? 0 ;
    }
    else
    {
        $x = $vector['x'];
        $y = $vector['y'];
    }

    $radius = sqrt($x ** 2 + $y ** 2);
    $angle  = atan2( $y , $x ) ; // returns radians

    if ( $degrees )
    {
        $angle = rad2deg($angle);
        // Fix angle to be between 0 and 360
        $angle = ($angle < 0) ? ($angle + 360) : $angle;
    }

    return
    [
        'angle'  => $angle,
        'radius' => $radius,
    ];
}