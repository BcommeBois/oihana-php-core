<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;
use stdClass;

//  * @param bool         $copy      If true, returns a deep copy and leaves the original unmodified.

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
 * @param array|object $document  The source document (array or object).
 * @param string       $key       The key or property name to set.
 * @param mixed        $value     The value to assign.
 * @param string       $separator Separator for nested keys. Default is '.'.
 * @param bool|null    $isArray   Optional: true if document is an array, false if object, null to auto-detect.
 *
 * @return array|object The modified document with the updated key/value.
 *
 * @throws InvalidArgumentException If the type override is inconsistent with the actual type.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function setKeyValue
(
    array|object $document ,
          string $key ,
           mixed $value ,
          string $separator = '.' ,
           ?bool $isArray = null ,
            // bool $copy = false
)
:array|object
{
    $isArray ??= is_array( $document ) ;

    if ( $isArray && !is_array( $document ) )
    {
        throw new InvalidArgumentException( sprintf('Invalid type override: expected array, got %s.', get_debug_type( $document ) ) );
    }

    if ( !$isArray && !is_object( $document ) )
    {
        throw new InvalidArgumentException( sprintf('Invalid type override: expected object, got %s.', get_debug_type( $document ) ) );
    }

    // $document = $copy ? unserialize(serialize($document)) : $copy ; // Deep copy

    $keys    = explode( $separator , $key ) ;
    $current = &$document;

    while ( count( $keys ) > 1 )
    {
        $segment = array_shift($keys);

        if ( $isArray )
        {
            if ( !isset( $current[ $segment ] ) || !is_array( $current[ $segment ] ) )
            {
                $current[ $segment ] = [] ;
            }
            $current = &$current[ $segment ] ;
        }
        else
        {
            if ( !isset( $current->{ $segment } ) || !is_object( $current->{ $segment } ) )
            {
                $current->{ $segment } = new stdClass() ;
            }
            $current = &$current->{ $segment } ;
        }
    }

    $lastKey = array_shift( $keys ) ;

    if ( $isArray )
    {
        $current[ $lastKey ] = $value;
    }
    else
    {
        $current->{ $lastKey } = $value;
    }

    return $document;
}