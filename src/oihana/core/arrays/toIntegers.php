<?php

namespace oihana\core\arrays ;

/**
 * Filters a value down to a list of integers, keeping every integer and every string that writes one, and dropping the rest.
 *
 * The value is first passed through {@see toArray()} (a bare scalar is wrapped as a
 * single-element list), then walked : an `int` is kept as is ; a `string` is kept,
 * cast to `int`, when it writes an integer and nothing else — an optional sign, digits,
 * surrounding spaces allowed, leading zeros included (`'007'` gives `7`). Anything else
 * is dropped : a `float` — even `350.0` —, a string holding a decimal, an exponent or a
 * hexadecimal number, a `bool`, `null`, an array, an object.
 *
 * Unlike {@see \oihana\core\numbers\toInt()}, which converts one value and falls back
 * to `0`, this filters a list : what is not an integer has no place in it, and a
 * decimal is never truncated into one.
 *
 * @param mixed $value The value to filter — an array, or a bare scalar wrapped via `toArray()`.
 *
 * @return array<int, int> The kept items, as integers, in their order, duplicates included, re-indexed from `0`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\toIntegers;
 *
 * toIntegers( [ 12 , '34' , ' 56 ' , '007' , 7.5 , '8.5' , true , null ] ) ; // [ 12 , 34 , 56 , 7 ]
 * toIntegers( 42 )                                                        ; // [ 42 ]
 * toIntegers( null )                                                      ; // []
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function toIntegers( mixed $value ) :array
{
    $integers = [] ;

    foreach ( toArray( $value ?? [] ) as $item )
    {
        if ( is_int( $item ) )
        {
            $integers[] = $item ;
        }
        elseif ( is_string( $item ) && preg_match( '/^\s*[+-]?\d+\s*$/' , $item ) === 1 )
        {
            $integers[] = (int) $item ;
        }
    }

    return $integers ;
}
