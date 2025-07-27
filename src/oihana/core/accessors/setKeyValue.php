<?php

declare(strict_types=1);

namespace oihana\core\helpers ;

use InvalidArgumentException;

/**
 * Sets the value associated with a given key in an array or object.
 *
 * This helper function assigns the given value to the specified key/property
 * in the provided document, which can be either an associative array or an object.
 *
 * The document type can be explicitly specified by `$isArray`. If null,
 * the type is inferred automatically.
 *
 * If a mismatch occurs between the forced type and the actual document type,
 * an InvalidArgumentException is thrown.
 *
 * @param array|object $document The source document (array or object).
 * @param string       $key      The key or property name to set.
 * @param mixed        $value    The value to assign.
 * @param bool|null    $isArray  Optional: true if document is an array, false if object, null to auto-detect.
 *
 * @return array|object The modified document with the updated key/value.
 *
 * @throws InvalidArgumentException If the type override is inconsistent with the actual type.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function setKeyValue( array|object $document , string $key , mixed $value , ?bool $isArray = null ) :array|object
{
    $isArray ??= is_array( $document ) ;

    if ( $isArray )
    {
        if ( !is_array( $document ) )
        {
            throw new InvalidArgumentException
            (
                sprintf('Invalid type override: expected array, got %s.', get_debug_type($document))
            );
        }

        $document[ $key ] = $value;

        return $document ;
    }

    if ( !is_object( $document ) )
    {
        throw new InvalidArgumentException
        (
            sprintf('Invalid type override: expected object, got %s.', get_debug_type( $document ) )
        );
    }

    $document->{ $key } = $value ;

    return $document;
}