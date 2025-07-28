<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;

/**
 * Validates the key, separator, and type of the provided document before performing key-based operations.
 *
 * This internal helper ensures that:
 * - The key is not empty.
 * - The separator is not empty.
 * - The document matches the expected type (`array` or `object`), or infers the type if `$isArray` is null.
 *
 * If a mismatch between the inferred or forced type and the actual document type occurs,
 * an `InvalidArgumentException` is thrown.
 *
 * @param array|object $document The input document (either an array or an object).
 * @param string $key The key or property name/path to validate.
 * @param string $separator The separator for nested paths (default is '.').
 * @param bool|null $isArray Optional reference: `true` for array, `false` for object, `null` to infer automatically.
 *
 * @return bool Returns the resolved value of `$isArray`: `true` for array, `false` for object.
 *
 * @throws InvalidArgumentException If the key or separator is empty, or the document type does not match `$isArray`.
 *
 * @example
 * Returns true:
 * ```php
 * $doc = ['foo' => 'bar'];
 * assertDocumentKeyValid( $doc , 'foo' ) ;
 * ```
 *
 * Returns false, sets $isArray = false
 * ```php
 * $doc = (object)['foo' => 'bar'];
 * assertDocumentKeyValid( $doc , 'foo' , '.' , $isArray ) ;
 * ```
 *
 * Returns true, sets $isArray = true
 * ```php
 * $doc = ['foo' => 'bar'];
 * assertDocumentKeyValid( $doc , 'foo' , '.' , $isArray ) ;
 * ```
 *
 * Throws InvalidArgumentException: "Key cannot be empty."
 * ```php
 * $doc = ['foo' => 'bar'];
 * assertDocumentKeyValid( $doc , '' , '.' ) ;
 * ```
 *
 * Throws InvalidArgumentException: "Separator cannot be empty."
 * ```php
 * $doc = (object)['foo' => 'bar'] ;
 * assertDocumentKeyValid( $doc , 'foo' , '' ) ;
 * ```
 *
 * Throws InvalidArgumentException: "Type mismatch: expected object, got array."
 * ```php
 * $doc = ['foo' => 'bar'];
 * assertDocumentKeyValid( $doc , 'foo' , '.' , false ) ;
 * ```
 *
 * Throws InvalidArgumentException: "Type mismatch: expected array, got stdClass."
 * ```php
 * $doc = new stdClass;
 * assertDocumentKeyValid( $doc , 'foo' , '.' , true ) ;
 * ```
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function assertDocumentKeyValid
(
    array|object $document ,
          string $key ,
          string $separator = '.'  ,
           ?bool &$isArray   = null
)
:bool
{
    if ( $key === '' )
    {
        throw new InvalidArgumentException('Key cannot be empty.');
    }

    if ( $separator === '' )
    {
        throw new InvalidArgumentException('Separator cannot be empty.');
    }

    $isArray ??= is_array( $document ) ;

    if ( $isArray && !is_array( $document ) )
    {
        throw new InvalidArgumentException( sprintf('Type mismatch: expected array, got %s.', get_debug_type( $document ) ) );
    }

    if ( !$isArray && !is_object( $document ) )
    {
        throw new InvalidArgumentException( sprintf('Type mismatch: expected object, got %s.' , get_debug_type( $document ) ) );
    }

    return $isArray;
}