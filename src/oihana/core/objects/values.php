<?php

namespace oihana\core\objects ;

/**
 * Returns the list of public property values of an object.
 *
 * Only the accessible (public and dynamic) property values are returned, in
 * declaration order, mirroring the behaviour of {@see get_object_vars()} from outside
 * the class.
 *
 * @param object $object The source object.
 *
 * @return array A list of the object's public property values.
 *
 * @example
 * ```php
 * use function oihana\core\objects\values;
 *
 * $user = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
 *
 * values( $user ) ; // [ 42 , 'Alice' ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function values( object $object ): array
{
    return array_values( get_object_vars( $object ) ) ;
}
