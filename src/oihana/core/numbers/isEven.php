<?php

namespace oihana\core\numbers ;

/**
 * Tells whether an integer is even.
 *
 * @param int $value The integer to test.
 *
 * @return bool `true` if `$value` is even, `false` otherwise.
 *
 * @example
 * ```php
 * use function oihana\core\numbers\isEven;
 *
 * isEven( 4 )  ; // true
 * isEven( 7 )  ; // false
 * isEven( 0 )  ; // true
 * isEven( -2 ) ; // true
 * ```
 *
 * @package oihana\core\numbers
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function isEven( int $value ) :bool
{
    return $value % 2 === 0 ;
}
