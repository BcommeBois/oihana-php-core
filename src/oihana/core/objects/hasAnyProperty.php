<?php

namespace oihana\core\objects ;

/**
 * Check if at least one of the given properties exists in the object.
 *
 * If $notNull is set to true, the property must also be non-null.
 *
 * @param object $object    The object to inspect
 * @param array $properties List of property names to check
 * @param bool $notNull     Whether to check for non-null values (default: false)
 *
 * @return bool True if at least one property exists (and is not null if $notNull = true)
 *
 * @example Recursive compression
 * ```php
 * $doc = (object)
 * [
 *    'id'     => 123  ,
 *    'slogan' => null ,
 * ];
 *
 * $props = [ 'slogan' , 'description' ] ;
 *
 * hasAnyProperty( $doc , $props ) ; // true  (because 'slogan' exists)
 * hasAnyProperty( $doc , $props , true ) ; // false (because 'slogan' is null)
 * ```
 *
 * @package oihana\core\objects
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function hasAnyProperty( object $object , array $properties , bool $notNull = false ): bool
{
    foreach ( $properties as $prop )
    {
        if ( $notNull )
        {
            if ( isset( $object->$prop ) )
            {
                return true ;
            }
        }
        else
        {
            if ( property_exists( $object , $prop ) )
            {
                return true ;
            }
        }
    }
    return false ;
}