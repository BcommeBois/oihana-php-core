<?php

namespace oihana\core\objects ;

/**
 * Check if all of the given properties exist in the object.
 *
 * If $notNull is set to true, each property must also be non-null.
 *
 * @param object $object     The object to inspect
 * @param array  $properties List of property names to check
 * @param bool   $notNull    Whether to check for non-null values (default: false)
 *
 * @return bool True if all properties exist (and are not null if $notNull = true)
 *
 * @example Usage
 * ```php
 * $doc = (object)
 * [
 *     'id'     => 123,
 *     'slogan' => 'Hello'
 * ];
 *
 * $props = ['id', 'slogan'];
 *
 * hasAllProperties($doc, $props);        // true
 * hasAllProperties($doc, $props, true);  // true
 *
 * $props2 = ['id', 'description'] ;
 * hasAllProperties($doc, $props2) ; // false (description missing)
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function hasAllProperties( object $object , array $properties , bool $notNull = false ): bool
{
    foreach ( $properties as $prop )
    {
        if ( $notNull )
        {
            if ( ! isset( $object->$prop ) )
            {
                return false ;
            }
        }
        else
        {
            if ( ! property_exists( $object , $prop ) )
            {
                return false ;
            }
        }
    }
    return true ;
}