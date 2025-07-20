<?php

namespace oihana\core\maths ;
/**
 * Rounds and returns the rounded value of the specified number or expression.
 *
 * @param int|float $value The number to round.
 * @param int $floatCount The number of decimal places to round to.
 * @return int|float The rounded number.
 *
 * @example
 * ```php
 * echo roundValue(4.9876);       // Outputs: 5
 * echo roundValue(4.9876, 2);    // Outputs: 4.99
 * echo roundValue(4.1234, 3);    // Outputs: 4.123
 * ```
 *
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function roundValue( int|float $value , int $floatCount = 0 ) : int|float
{
    $r = 1 ;
    $i = - 1 ;
    while ( ++ $i < $floatCount )
    {
        $r *= 10 ;
    }
    return round( $value * $r ) / $r  ;
}