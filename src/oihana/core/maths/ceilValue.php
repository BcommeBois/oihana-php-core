<?php

namespace oihana\core\maths ;

/**
 * Rounds and returns the ceiling of the specified number or expression.
 * The ceiling of a number is the closest integer that is greater than or equal to the number.
 * @function
 * @instance
 * @param int|float $value - The number to round.
 * @param int  $floatCount - The count of number after the point.
 * @return int|float
 * @example
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