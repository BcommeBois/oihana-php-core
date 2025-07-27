<?php

declare(strict_types=1);

namespace oihana\core\accessors ;

use InvalidArgumentException;
use stdClass;
use function oihana\core\arrays\ensureArrayPath;
use function oihana\core\arrays\setArrayValue;
use function oihana\core\objects\ensureObjectPath;
use function oihana\core\objects\setObjectValue;

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

    $keys    = explode( $separator , $key ) ;
    $current = &$document;

    for ( $i = 0 ; $i < count( $keys ) - 1 ; $i++ )
    {
        $segment = $keys[ $i ] ;

        if (  $isArray )
        {
            $current = &ensureArrayPath($current , $segment ) ;
        }
        else
        {
            $current = &ensureObjectPath($current , $segment ) ;
        }
    }

    $lastKey = end( $keys ) ;

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