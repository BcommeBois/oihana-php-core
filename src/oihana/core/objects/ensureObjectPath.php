<?php

namespace oihana\core\objects ;

use stdClass;

/**
 * Ensures that a given property of an object is initialized as an object.
 *
 * If the property does not exist or is not an object, it will be replaced with a new stdClass instance.
 * The function returns a reference to the nested object, allowing direct modification.
 *
 * This is useful when building or navigating nested object structures dynamically.
 *
 * @param object $current  The current object in which the property is ensured.
 * @param string $segment  The property name to ensure as an object.
 *
 * @return object A reference to the ensured nested object (stdClass).
 *
 * @example
 * ```php
 * $data = new stdClass();
 * $ref =& ensureObjectPath($data, 'config');
 * $ref->enabled = true;
 * // $data now contains: (object)['config' => (object)['enabled' => true]]
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function &ensureObjectPath( object &$current , string $segment ): object
{
    if ( !isset( $current->{ $segment } ) || !is_object( $current->{ $segment } ) )
    {
        $current->{ $segment } = new stdClass() ;
    }

    return $current->{ $segment } ;
}