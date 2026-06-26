<?php

namespace oihana\core\objects ;

use stdClass ;

/**
 * Removes the specified public properties from an object.
 *
 * Returns a new `stdClass` containing every public property of the source object
 * except the listed ones. The source object is never modified. This is the inverse
 * of {@see pick()}.
 *
 * @param object $object The source object.
 * @param array<int, string> $keys   The list of property names to remove.
 *
 * @return object A new `stdClass` without the omitted properties.
 *
 * @example
 * ```php
 * use function oihana\core\objects\omit;
 *
 * $user = (object) [ 'id' => 42 , 'name' => 'Alice' , 'password' => 'secret' ] ;
 *
 * $result = omit( $user , [ 'password' ] ) ;
 * // (object) [ 'id' => 42 , 'name' => 'Alice' ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function omit( object $object , array $keys ): object
{
    $result = new stdClass() ;
    foreach ( get_object_vars( $object ) as $key => $value )
    {
        if ( !in_array( $key , $keys , true ) )
        {
            $result->{ $key } = $value ;
        }
    }
    return $result ;
}
