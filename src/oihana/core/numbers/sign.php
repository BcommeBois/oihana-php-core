<?php

namespace oihana\core\numbers ;

/**
 * Returns the sign of a number.
 *
 * @param int|float $value The value to evaluate.
 *
 * @return int `-1` if the value is negative, `1` if it is positive, `0` if it is zero.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\sign;
 *
 * sign( -42 )  ; // -1
 * sign( 0 )    ; //  0
 * sign( 3.14 ) ; //  1
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function sign( int|float $value ) :int
{
    return $value <=> 0 ;
}
