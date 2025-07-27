<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;
use function oihana\core\arrays\setArrayValue;
use function oihana\core\objects\setObjectValue;

/**
 * Checks whether a key or property exists in the document (array or object), including nested paths.
 *
 * Supports dot notation (or custom separator) for nested keys, and allows forcing the type.
 * If the path is not fully present, returns false. Objects with __isset or dynamic __get are not assumed present.
 *
 * @param array|object $document  The document (array or object).
 * @param string       $key       The key or property path (e.g., 'user.name').
 * @param string       $separator Separator used for nested keys (default: '.').
 * @param bool|null    $isArray   Optional: true for array, false for object, null to auto-detect.
 *
 * @return bool True if the path exists entirely, false otherwise.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function hasKeyValue
(
    array|object $document ,
          string $key ,
          string $separator = '.'  ,
           ?bool $isArray   = null
)
:bool
{
    $isArray = assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;

    if ( !str_contains($key, $separator) )
    {
        if ( $isArray )
        {
            return array_key_exists( $key , $document ) ;
        }

        return property_exists( $document , $key ) ;
    }

    $keys    = explode( $separator , $key ) ;
    $current = &$document;

    foreach ( $keys as $segment )
    {
        if ( $isArray )
        {
            if ( !is_array( $current ) || !array_key_exists( $segment , $current ) )
            {
                return false;
            }
            $current = $current[ $segment ] ;
        }
        else
        {
            if ( !is_object( $current ) || !property_exists( $current , $segment ) )
            {
                return false;
            }
            $current = $current->{ $segment } ;
        }
    }

    return true ;
}