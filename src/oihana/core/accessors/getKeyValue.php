<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

/**
 * Retrieves the value associated with a given key from an array or object.
 *
 * This helper function returns the value associated with the specified key/property
 * in the provided document, which can be either an associative array or an object.
 * Supports nested keys using a separator (default is '.').
 *
 * The document type can be explicitly specified by `$isArray`. If null,
 * the type is inferred automatically.
 *
 * If a mismatch occurs between the forced type and the actual document type,
 * an InvalidArgumentException is thrown.
 *
 * @param array|object $document  The source document (array or object).
 * @param string       $key       The key or property name to retrieve (supports nested keys with separator).
 * @param mixed        $default   The default value to return if the key is not found. Default is null.
 * @param string       $separator Separator for nested keys. Default is '.'.
 * @param bool|null    $isArray   Optional: true if document is an array, false if object, null to auto-detect.
 *
 * @return mixed The value of the key if found, or `null` if the key/property does not exist.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function getKeyValue
(
    array|object $document ,
          string $key ,
           mixed $default   = null ,
          string $separator = '.'  ,
           ?bool $isArray   = null
)
:mixed
{
    $isArray = assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;

    if ( !str_contains($key, $separator) )
    {
        if ( $isArray )
        {
            return $document[ $key ] ?? $default ;
        }

        return $document->{ $key } ?? $default ;
    }

    $keys    = explode( $separator , $key ) ;
    $current = &$document;

    foreach ( $keys as $segment )
    {
        if ( $isArray )
        {
            if ( !is_array( $current ) || !array_key_exists( $segment , $current ) )
            {
                return $default;
            }
            $current = $current[ $segment ] ;
        }
        else
        {
            if ( !is_object( $current ) || !property_exists( $current , $segment ) )
            {
                return $default;
            }
            $current = $current->{ $segment } ;
        }
    }

    return $current;
}