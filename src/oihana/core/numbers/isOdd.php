<?php

namespace oihana\core\numbers ;

/**
 * Tells whether an integer is odd.
 *
 * @param int $value The integer to test.
 *
 * @return bool `true` if `$value` is odd, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\isOdd;
 *
 * isOdd( 7 )  ; // true
 * isOdd( 4 )  ; // false
 * isOdd( 0 )  ; // false
 * isOdd( -3 ) ; // true
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function isOdd( int $value ) :bool
{
    return !isEven( $value ) ;
}
