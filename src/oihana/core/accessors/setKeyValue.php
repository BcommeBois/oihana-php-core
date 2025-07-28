<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;

use function oihana\core\arrays\setArrayValue;
use function oihana\core\objects\setObjectValue;

/**
 * Sets a value in an array or object using a dot-notated key path.
 *
 * This function assigns a value to a key or property in the given array or object.
 * It supports nested assignment using a separator (default: '.').
 * If any intermediate path segment does not exist, it is created automatically.
 *
 * The type of the structure can be explicitly forced with `$isArray`, or inferred automatically.
 *
 * @param array|object $document The target array or object to modify.
 * @param string $key The key or property name to set. Supports nesting (e.g., "user.name").
 * @param mixed $value The value to assign.
 * @param string $separator Separator used to split nested keys. Default is '.'.
 * @param bool|null $isArray Optional: true for array mode, false for object mode, null to auto-detect.
 *
 * @return array|object The updated document after the value has been set.
 *
 * @throws InvalidArgumentException If the provided type does not match the structure type.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 *
 * @example
 * ```php
 * $doc = ['name' => 'Alice'];
 * $doc = setKeyValue($doc, 'age', 30);
 * // Result: ['name' => 'Alice', 'age' => 30]
 * ```
 *
 * ```php
 * $doc = ['user' => ['name' => 'Alice']];
 * $doc = setKeyValue($doc, 'user.age', 30);
 * // Result: ['user' => ['name' => 'Alice', 'age' => 30]]
 * ```
 *
 * ```php
 * $doc = (object)['user' => (object)['name' => 'Alice']];
 * $doc = setKeyValue($doc, 'user.age', 30);
 * // Result: (object)['user' => (object)['name' => 'Alice', 'age' => 30]]
 * ```
 *
 * ```php
 * $doc = [];
 * $doc = setKeyValue($doc, 'config.debug.enabled', true);
 * // Result: ['config' => ['debug' => ['enabled' => true]]]
 * ```
 *
 * ```php
 * $doc = (object)[];
 * $doc = setKeyValue($doc, 'meta.version.major', 1);
 * // Result: (object)['meta' => (object)['version' => (object)['major' => 1]]]
 * ```
 */
function setKeyValue
(
    array|object $document ,
          string $key ,
           mixed $value ,
          string $separator = '.' ,
           ?bool $isArray = null ,
)
:array|object
{
    $isArray = assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;

    if ( !str_contains( $key , $separator ) )
    {
        return $isArray
             ? setArrayValue  ( $document , $key , $value )
            : setObjectValue ( $document , $key , $value ) ;
    }

    $keys    = explode($separator, $key ) ;
    $parent  = &resolveReferencePath( $document , $keys , $isArray ) ;
    $lastKey = end($keys ) ;

    if ( $isArray )
    {
        $parent[ $lastKey ] = $value;
    }
    else
    {
        $parent->{ $lastKey } = $value;
    }

    return $document;
}