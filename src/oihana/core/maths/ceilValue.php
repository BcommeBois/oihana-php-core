<?php

namespace oihana\core\maths ;

/**
 * Rounds and returns the ceiling of the specified number or expression.
 * The ceiling of a number is the closest integer that is greater than or equal to the number.
 *
 * @param int|float $value The number to round.
 * @param int $floatCount The number of decimal places to round up to.
 * @return int|float The rounded number.
 *
 * @example
 * ```php
 * echo ceilValue(4.1234);       // Outputs: 5
 * echo ceilValue(4.1234, 2);    // Outputs: 4.13
 * echo ceilValue(4.9999, 3);    // Outputs: 5.000
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function ceilValue( int|float $value , int $floatCount = 0 ) : int|float
{
    $r = 1 ;
    $i = - 1 ;
    while ( ++ $i < $floatCount )
    {
        $r *= 10 ;
    }
    return ceil( $value * $r ) / $r  ;
}