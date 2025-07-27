<?php

declare(strict_types=1);

namespace oihana\core\helpers ;

use InvalidArgumentException;

/**
 * Retrieves the value of a given key from an array or object.
 *
 * This helper function returns the value associated with a specified key in the provided document,
 * which can be either an associative array or a standard object. If the key does not exist, it returns null.
 *
 * If the `$isArray` parameter is not provided (null), the function will automatically determine
 * whether the document is an array or an object. Otherwise, it uses the given boolean value to
 * interpret the type of document:
 * - `true`: the document is treated as an array.
 * - `false`: the document is treated as an object.
 *
 * @param array|object $document The source document, either an array or an object.
 * @param string       $key      The key or property name to retrieve.
 * @param bool|null    $isArray  (Optional) Whether the document is an array (`true`) or an object (`false`).
 *                               If `null`, the type is automatically detected.
 *
 * @return mixed The value of the key if found, or `null` if the key/property does not exist.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function getKeyValue( array|object $document , string $key , ?bool $isArray = null ) :mixed
{
    $isArray ??= is_array( $document ) ;

    if ( $isArray )
    {
        if ( !is_array( $document ) )
        {
            throw new InvalidArgumentException
            (
                sprintf('Invalid type override: expected array, got %s.' , get_debug_type( $document ) )
            );
        }
        return $document[ $key ] ?? null;
    }

    if ( !is_object( $document ) )
    {
        throw new InvalidArgumentException
        (
            sprintf('Invalid type override: expected object, got %s.', get_debug_type( $document ))
        );
    }

    return $document->{ $key } ?? null;
}