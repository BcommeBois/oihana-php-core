<?php

namespace oihana\core\maths ;

/**
 * Rounds a number up to a count of decimal places.
 *
 * The ceiling of a number is the closest integer that is greater than or equal to it.
 *
 * A decimal number is almost never exact in memory : `1.1 * 100` holds `110.00000000000001`, and a
 * naive `ceil( $value * $r ) / $r` climbs to `1.11`. The scaled value is therefore rounded to six
 * decimals **before** the ceiling is taken : a scaled value within one millionth of an integer is
 * treated as sitting exactly on it, so only a real excess pushes the result up.
 *
 * @param int|float $value      The number to round.
 * @param int       $floatCount The number of decimal places to round up to. A negative count is read as `0`.
 *
 * @return float The rounded number.
 *
 * @example
 * ```php
 * use function oihana\core\maths\ceilValue;
 *
 * echo ceilValue( 4.1234 )    ; // 5
 * echo ceilValue( 4.1234 , 2 ) ; // 4.13
 * echo ceilValue( 4.9999 , 3 ) ; // 5
 * echo ceilValue( 1.1 , 2 )    ; // 1.1  — not 1.11 : the float noise is ignored
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function ceilValue( int|float $value , int $floatCount = 0 ) : float
{
    $r = 10 ** max( 0 , $floatCount ) ;

    return ceil( round( $value * $r , 6 ) ) / $r ;
}
