<?php

namespace oihana\core\numbers ;

/**
 * Splits a number into its integral and fractional parts, like the C `modf()` function.
 *
 * The number is truncated toward zero, so the sign is preserved on both parts :
 * `modf( -1.5 )` returns `[ -1.0 , -0.5 ]` (and not `[ -2.0 , 0.5 ]` as a `floor()`
 * based split would). Both parts are always returned as `float`.
 *
 * Special values follow the C behavior :
 * - `modf( INF )`  returns `[ INF , 0.0 ]` (idem with `-INF`).
 * - `modf( NAN )`  returns `[ NAN , NAN ]`.
 *
 * Note: unlike C's and Python's `modf`, the integral part comes **first**
 * (natural reading order : `1.5` is `1 + 0.5`), the fractional part second.
 *
 * @param float $number The number to split.
 *
 * @return array{ 0: float , 1: float } A two-element array : `[ integral part , fractional part ]`.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\modf;
 *
 * modf( 1.5 )   ; // [ 1.0 , 0.5 ]
 * modf( -1.5 )  ; // [ -1.0 , -0.5 ]
 * modf( 3.0 )   ; // [ 3.0 , 0.0 ]
 * modf( -0.25 ) ; // [ -0.0 , -0.25 ]
 * modf( INF )   ; // [ INF , 0.0 ]
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.11
 */
function modf( float $number ) :array
{
    if ( is_infinite( $number ) )
    {
        return [ $number , 0.0 ] ;
    }

    $integral = $number < 0 ? ceil( $number ) : floor( $number ) ; // truncation toward zero

    return [ $integral , $number - $integral ] ;
}
