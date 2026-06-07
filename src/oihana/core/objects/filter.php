<?php

namespace oihana\core\objects ;

use stdClass ;

/**
 * Keeps the public properties of an object that satisfy a predicate.
 *
 * Returns a new `stdClass` containing only the properties for which
 * `fn( $value , $key )` returns a truthy value. The source object is never modified.
 *
 * @param object   $object The source object.
 * @param callable $fn     The predicate callback: `fn( $value , $key ): bool`.
 *
 * @return object A new `stdClass` with only the kept properties.
 *
 * @example
 * ```php
 * use function oihana\core\objects\filter;
 *
 * $values = (object) [ 'a' => 1 , 'b' => 2 , 'c' => 3 ] ;
 *
 * $result = filter( $values , fn( $v ) => $v % 2 === 1 ) ;
 * // (object) [ 'a' => 1 , 'c' => 3 ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function filter( object $object , callable $fn ): object
{
    $result = new stdClass() ;
    foreach ( get_object_vars( $object ) as $key => $value )
    {
        if ( $fn( $value , $key ) )
        {
            $result->{ $key } = $value ;
        }
    }
    return $result ;
}
