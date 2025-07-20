<?php

namespace oihana\core\numbers ;

/**
 * Bounds a number value between two numbers.
 *
 * @param float $value The value to clamp.
 * @param float $min The minimum value of the range.
 * @param float $max The maximum value of the range.
 * @return float A value bounded between $min and $max.
 *
 * @example
 * ```php
 * clip( 4  , 5 , 10 ) ;  // Returns 5
 * clip( 12 , 5 , 10 ) ; // Returns 10
 * clip( 6  , 5 , 10 ) ;  // Returns 6
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
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