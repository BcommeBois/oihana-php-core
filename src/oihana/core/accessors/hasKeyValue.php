<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use Exception;
use InvalidArgumentException;

/**
 * Checks whether a given key or property exists in an array or object, including nested paths.
 *
 * This helper determines if the specified key exists in the given document (array or object).
 * It supports nested access via a separator (default is '.') and can optionally force
 * the document type (array or object).
 *
 * If any part of the path does not exist, `false` is returned. This function does not rely on
 * `__get()` or `__isset()` magic methods for objects.
 *
 * @param array|object $document The document (array or object) to inspect.
 * @param string $key The key or property path to check. Supports nesting with separator.
 * @param string $separator Separator used for nested paths. Default is '.'.
 * @param bool|null $isArray Optional: true if document is an array, false if object, null to auto-detect.
 *
 * @return bool True if the full key path exists, false otherwise.
 *
 * @throws InvalidArgumentException If the document or key is invalid.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 *
 * @example
 * ```php
 * $doc = ['name' => 'Alice'];
 * hasKeyValue($doc, 'name'); // true
 * hasKeyValue($doc, 'age');  // false
 * ```
 *
 * ```php
 * $doc = ['user' => ['name' => 'Alice']];
 * hasKeyValue($doc, 'user.name'); // true
 * hasKeyValue($doc, 'user.age');  // false
 * ```
 *
 * ```php
 * $doc = (object)['user' => (object)['name' => 'Alice']];
 * hasKeyValue($doc, 'user.name'); // true
 * hasKeyValue($doc, 'user.age');  // false
 * ```
 *
 * ```php
 * $doc = [];
 * hasKeyValue($doc, 'config.debug.enabled'); // false
 * ```
 *
 * ```php
 * $doc = (object)[];
 * hasKeyValue($doc, 'meta.version.major'); // false
 * ```
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

    try
    {
        $keys    = explode( $separator , $key ) ;
        $parent  = &resolveReferencePath($document ,  $keys , $isArray ) ;
        $lastKey = end( $keys ) ;

        if ( $isArray )
        {
            return isset( $parent[ $lastKey ] ) ;
        }
        else
        {
            return isset( $parent->{ $lastKey } ) ;
        }
    }
    catch ( Exception $e )
    {
        return false;
    }
}