<?php

namespace oihana\core\maths ;

/**
 * Rounds and returns a number by a count of floating points, using floor.
 * The floor of a number is the closest integer less than or equal to the number.
 *
 * @param int|float $value The number to round.
 * @param int $floatCount The number of decimal places to round down to.
 * @return int|float The rounded number.
 *
 * @example
 * ```php
 * echo floorValue(4.9876);       // Outputs: 4
 * echo floorValue(4.9876, 2);    // Outputs: 4.98
 * echo floorValue(4.1234, 3);    // Outputs: 4.123
 * ```
 * @package oihana\core\maths
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function floorValue( int|float $value , int $floatCount = 0 ) : int|float
{
    $r = 1 ;
    $i = - 1 ;
    while ( ++ $i < $floatCount )
    {
        $r *= 10 ;
    }
    return floor( $value * $r ) / $r  ;
}