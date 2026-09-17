<?php

namespace oihana\core\maths ;

/**
 * Rounds a number to a count of decimal places.
 *
 * A decimal number is almost never exact in memory : `0.235 * 2.5 * 1000` holds `587.4999999999999`,
 * and a naive `round( $value * $r ) / $r` falls to `0.587`. The scaled value is therefore rounded to
 * six decimals **first** : a scaled value within one millionth of a half-way point is treated as
 * sitting exactly on it, so it rounds the way the exact arithmetic would.
 *
 * @param int|float $value      The number to round.
 * @param int       $floatCount The number of decimal places to round to. A negative count is read as `0`.
 *
 * @return float The rounded number.
 *
 * @example
 * ```php
 * use function oihana\core\maths\roundValue;
 *
 * echo roundValue( 4.9876 )            ; // 5
 * echo roundValue( 4.9876 , 2 )        ; // 4.99
 * echo roundValue( 4.1234 , 3 )        ; // 4.123
 * echo roundValue( 0.235 * 2.5 , 3 )   ; // 0.588 — not 0.587 : the float noise is ignored
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function roundValue( int|float $value , int $floatCount = 0 ) : float
{
    $r = 10 ** max( 0 , $floatCount ) ;

    return round( round( $value * $r , 6 ) ) / $r ;
}
