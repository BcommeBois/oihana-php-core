<?php

namespace oihana\core\numbers ;

/**
 * Tells whether a ratio is within a relative tolerance of a target.
 *
 * Compares `abs( $ratio - $target )` against `abs( $target ) * $tolerance` — the
 * allowed gap scales with the target's magnitude, so the same `$tolerance` reads
 * as a *relative* margin (e.g. `0.05` for "within 5%") rather than an absolute one,
 * regardless of the sign of `$target`.
 *
 * @param float $ratio     The value to test.
 * @param float $target    The reference value.
 * @param float $tolerance The relative tolerance, as a fraction of `$target` (e.g. `0.05` for 5%).
 *
 * @return bool `true` if `$ratio` lies within the tolerance band around `$target`, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\isNear;
 *
 * isNear( 104 , 100 , 0.05 ) ; // true  (4% off, within 5%)
 * isNear( 110 , 100 , 0.05 ) ; // false (10% off, beyond 5%)
 * isNear( 100 , 100 , 0.0 )  ; // true  (exact match)
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function isNear( float $ratio , float $target , float $tolerance ) :bool
{
    return abs( $ratio - $target ) <= abs( $target ) * $tolerance ;
}
