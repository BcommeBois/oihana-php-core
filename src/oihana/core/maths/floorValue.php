<?php

namespace oihana\core\maths ;

/**
 * Rounds a number down to a count of decimal places.
 *
 * The floor of a number is the closest integer that is less than or equal to it.
 *
 * A decimal number is almost never exact in memory : `8.2 * 100` holds `819.9999999999999`, and a
 * naive `floor( $value * $r ) / $r` drops to `8.19`. The scaled value is therefore rounded to six
 * decimals **before** the floor is taken : a scaled value within one millionth of an integer is
 * treated as sitting exactly on it, so only a real shortfall pushes the result down.
 *
 * @param int|float $value      The number to round.
 * @param int       $floatCount The number of decimal places to round down to. A negative count is read as `0`.
 *
 * @return float The rounded number.
 *
 * @example
 * ```php
 * use function oihana\core\maths\floorValue;
 *
 * echo floorValue( 4.9876 )     ; // 4
 * echo floorValue( 4.9876 , 2 ) ; // 4.98
 * echo floorValue( 4.1234 , 3 ) ; // 4.123
 * echo floorValue( 8.2 , 2 )    ; // 8.2  — not 8.19 : the float noise is ignored
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function floorValue( int|float $value , int $floatCount = 0 ) : float
{
    $r = 10 ** max( 0 , $floatCount ) ;

    return floor( round( $value * $r , 6 ) ) / $r ;
}
