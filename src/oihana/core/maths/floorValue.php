<?php

namespace oihana\core\maths ;

/**
 * Rounds and returns a number by a count of floating points.
 * @function
 * @instance
 * @param int|float $value - The number to round.
 * @param int  $floatCount - The count of number after the point.
 * @return int|float
 * @example
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