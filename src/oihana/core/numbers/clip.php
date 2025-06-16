<?php

namespace oihana\core\numbers ;

/**
 * Bounds a number value between 2 numbers.
 * @function
 * @instance
 * @param float  $value - The value to clamp.
 * @param float  $min - The min value of the range.
 * @param float  $max - The max value of the range.
 * @return float  A bound number value between 2 numbers.
 * @example
 * clip(4, 5, 10)  ; // 5
 * clip(12, 5, 10) ; // 10
 * clip(6, 5, 10)  ; // 6
 */
function clip( float $value , float $min , float $max ) :float
{
    if ( $value <= $min )
    {
        return $min ;
    }
    else if ( $value >= $max )
    {
        return $max ;
    }
    return $value ;
}