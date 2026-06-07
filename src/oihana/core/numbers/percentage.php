<?php

namespace oihana\core\numbers ;

/**
 * Computes which percentage a part represents of a total.
 *
 * Returns `( $part / $total ) * 100`. As a guard against division by zero, a
 * `$total` of `0` yields `0.0` rather than raising an error.
 *
 * @param int|float $part  The partial amount.
 * @param int|float $total The total amount.
 *
 * @return float The percentage in the `[0, 100]` range for in-bounds inputs, `0.0` when `$total` is `0`.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\percentage;
 *
 * percentage( 25 , 200 ) ; // 12.5
 * percentage( 1 , 3 )    ; // 33.333333333333
 * percentage( 5 , 0 )    ; // 0.0 (division-by-zero guard)
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function percentage( int|float $part , int|float $total ) :float
{
    if ( $total == 0 )
    {
        return 0.0 ;
    }
    return ( $part / $total ) * 100 ;
}
