<?php

namespace oihana\core\arrays ;

/**
 * Filters a value down to a list of strings, keeping every string and integer and dropping the rest.
 *
 * The value is first passed through {@see toArray()} (a bare scalar is wrapped as a
 * single-element list), then walked : `string` and `int` items are kept, cast to
 * `string` ; anything else (`float`, `bool`, `null`, arrays, objects, resources) is dropped.
 *
 * @param mixed $value The value to filter — an array, or a bare scalar wrapped via `toArray()`.
 *
 * @return array<int, string> The kept items, cast to string, re-indexed from `0`.
 *
 * @example
 * ```php
 * use function oihana\core\arrays\toStrings;
 *
 * toStrings( [ 'a' , 1 , 2.5 , null , 'b' ] ) ; // [ 'a' , '1' , 'b' ]
 * toStrings( 'solo' )                         ; // [ 'solo' ]
 * toStrings( null )                           ; // []
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.3.0
 */
function toStrings( mixed $value ) :array
{
    $strings = [] ;

    foreach ( toArray( $value ?? [] ) as $item )
    {
        if ( is_string( $item ) || is_int( $item ) )
        {
            $strings[] = (string) $item ;
        }
    }

    return $strings ;
}
