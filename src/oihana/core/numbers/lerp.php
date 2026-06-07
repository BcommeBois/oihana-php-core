<?php

namespace oihana\core\numbers ;

/**
 * Linearly interpolates between two values.
 *
 * Computes `$a + ( $b - $a ) * $t`. The factor `$t` is not bounded: values outside
 * the `[0, 1]` range extrapolate beyond the `[$a, $b]` segment.
 *
 * @param float $a The start value (returned when `$t === 0.0`).
 * @param float $b The end value (returned when `$t === 1.0`).
 * @param float $t The interpolation factor, typically in `[0, 1]`.
 *
 * @return float The interpolated value.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\lerp;
 *
 * lerp( 0.0 , 10.0 , 0.0 ) ;  // 0.0
 * lerp( 0.0 , 10.0 , 0.5 ) ;  // 5.0
 * lerp( 0.0 , 10.0 , 1.0 ) ;  // 10.0
 * lerp( 0.0 , 10.0 , 2.0 ) ;  // 20.0 (extrapolation)
 * lerp( 10.0 , 0.0 , 0.25 ) ; // 7.5
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function lerp( float $a , float $b , float $t ) :float
{
    return $a + ( $b - $a ) * $t ;
}
