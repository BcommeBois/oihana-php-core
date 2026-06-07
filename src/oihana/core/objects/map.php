<?php

namespace oihana\core\objects ;

use stdClass ;

/**
 * Transforms every public property value of an object through a callback.
 *
 * Returns a new `stdClass` with the same property names, each value replaced by the
 * result of `fn( $value , $key )`. The source object is never modified.
 *
 * @param object   $object The source object.
 * @param callable $fn     The mapping callback: `fn( $value , $key ): mixed`.
 *
 * @return object A new `stdClass` with mapped values.
 *
 * @example
 * ```php
 * use function oihana\core\objects\map;
 *
 * $prices = (object) [ 'a' => 10 , 'b' => 20 ] ;
 *
 * $result = map( $prices , fn( $v ) => $v * 2 ) ;
 * // (object) [ 'a' => 20 , 'b' => 40 ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function map( object $object , callable $fn ): object
{
    $result = new stdClass() ;
    foreach ( get_object_vars( $object ) as $key => $value )
    {
        $result->{ $key } = $fn( $value , $key ) ;
    }
    return $result ;
}
