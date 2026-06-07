<?php

namespace oihana\core\objects ;

use stdClass ;

/**
 * Keeps only the specified public properties of an object.
 *
 * Returns a new `stdClass` containing only the listed properties that actually exist
 * on the source object. The source object is never modified. Keys that are absent are
 * silently ignored.
 *
 * @param object $object The source object.
 * @param array  $keys   The list of property names to keep.
 *
 * @return object A new `stdClass` with only the picked properties.
 *
 * @example
 * ```php
 * use function oihana\core\objects\pick;
 *
 * $user = (object) [ 'id' => 42 , 'name' => 'Alice' , 'email' => 'alice@example.com' ] ;
 *
 * $result = pick( $user , [ 'id' , 'name' ] ) ;
 * // (object) [ 'id' => 42 , 'name' => 'Alice' ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function pick( object $object , array $keys ): object
{
    $vars   = get_object_vars( $object ) ;
    $result = new stdClass() ;
    foreach ( $keys as $key )
    {
        if ( array_key_exists( $key , $vars ) )
        {
            $result->{ $key } = $vars[ $key ] ;
        }
    }
    return $result ;
}
