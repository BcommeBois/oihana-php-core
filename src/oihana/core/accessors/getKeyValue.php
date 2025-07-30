<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use Exception;
use InvalidArgumentException;

/**
 * Retrieves a value from an array or object using a dot-notated key path.
 *
 * This function returns the value associated with a flat or nested key from the given
 * array or object. It supports nested keys using a separator (default: '.').
 * If the path does not exist or a type mismatch occurs, the `$default` value is returned.
 *
 * The structure type can be explicitly specified using `$isArray`, or it will be inferred automatically.
 *
 * @param array|object $document The source structure (array or object).
 * @param string $key The key or property to retrieve, supports nesting (e.g. 'user.name').
 * @param mixed $default The fallback value if the key does not exist. Default is `null`.
 * @param string $separator Separator used to split nested keys. Default is '.'.
 * @param bool|null $isArray Optional: true for array mode, false for object mode, null for auto-detection.
 *
 * @return mixed The value found, or the default if the key path is not valid or not found.
 *
 * @throws InvalidArgumentException If the structure type is invalid or mismatched.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 *
 * @example
 * Use a basic array key expression :
 * ```php
 * $doc = ['name' => 'Alice', 'age' => 30];
 * echo getKeyValue($doc, 'name'); // 'Alice'
 * ```
 *
 * Use a complex array key expression :
 * ```php
 * $doc = ['user' => ['name' => 'Alice']];
 * echo getKeyValue($doc, 'user.name'); // 'Alice'
 * ```
 *
 * Use with an object :
 * ```php
 * $doc = (object)['user' => (object)['email' => 'a@b.c']];
 * echo getKeyValue($doc, 'user.email'); // 'a@b.c'
 * ```
 *
 * Use a default value if the key not exist :
 * ```php
 * $doc = ['meta' => ['a' => 1]];
 * echo getKeyValue($doc, 'meta.b', 'default'); // 'default'
 *
 * $doc = (object)['a' => 1];
 * echo getKeyValue($doc, 'b', 42); // 42
 * ```
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

    if ( str_contains($key, $separator) )
    {
        $keys = explode($separator, $key);

        try
        {
            $parent  = &resolveReferencePath($document, $keys, $isArray);
            $lastKey = end( $keys ) ;
            return $isArray
                 ? ( $parent[ $lastKey ] ?? $default )
                 : $parent->{ $lastKey } ?? $default ;
        }
        catch ( Exception $exception )
        {
            return $default ;
        }
    }

    if ( $isArray )
    {
        return $document[ $key ] ?? $default ;
    }

    return $document->{ $key } ?? $default ;
}