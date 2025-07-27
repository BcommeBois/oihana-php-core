<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;

/**
 * Validates the key, separator, and document type before key-based operations.
 *
 * @param array|object $document
 * @param string       $key
 * @param string       $separator
 * @param bool|null    $isArray   Will be assigned if null
 *
 * @return bool Returns final $isArray value (true if array, false if object)
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