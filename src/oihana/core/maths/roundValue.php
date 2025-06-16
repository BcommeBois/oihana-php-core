<?php

namespace oihana\core\maths ;

/**
 * Rounds and returns the rounded value of the specified number or expression.
 * @function
 * @instance
 * @param int|float $value - The number to round.
 * @param int  $floatCount - The count of number after the point.
 * @return int|float
 * @example
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