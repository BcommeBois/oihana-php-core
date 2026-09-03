<?php

namespace oihana\core\numbers ;

/**
 * Converts a value to an integer, defaulting to zero.
 *
 * - Returns `$value` unchanged if it is already an `int`.
 * - Casts any other numeric value (numeric string, float, ...) to `int`.
 * - Returns `0` for anything non-numeric.
 *
 * @param mixed $value The value to convert.
 *
 * @return int The integer value of `$value`, or `0` if it can't be resolved to a number.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\toInt;
 *
 * toInt( 42 )    ; // 42
 * toInt( "42" )  ; // 42
 * toInt( 4.9 )   ; // 4
 * toInt( "abc" ) ; // 0
 * toInt( null )  ; // 0
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function toInt( mixed $value ) :int
{
    return is_int( $value ) ? $value : ( is_numeric( $value ) ? (int) $value : 0 ) ;
}
