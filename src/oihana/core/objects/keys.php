<?php

namespace oihana\core\objects ;

/**
 * Returns the list of public property names of an object.
 *
 * Only the accessible (public and dynamic) properties are returned, in declaration
 * order, mirroring the behaviour of {@see get_object_vars()} from outside the class.
 *
 * @param object $object The source object.
 *
 * @return list<string> A list of the object's public property names.
 *
 * @example
 * ```php
 * use function oihana\core\objects\keys;
 *
 * $user = (object) [ 'id' => 42 , 'name' => 'Alice' ] ;
 *
 * keys( $user ) ; // [ 'id' , 'name' ]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.9
 */
function keys( object $object ): array
{
    return array_keys( get_object_vars( $object ) ) ;
}
