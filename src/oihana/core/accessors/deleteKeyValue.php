<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use InvalidArgumentException;

/**
 * Deletes a value from an array or object using a dot-notated key path.
 *
 * This utility supports:
 * - Flat keys (e.g., "name")
 * - Nested keys (e.g., "user.profile.name")
 * - Wildcard deletion for sub-containers (e.g., "user.*")
 * - Global wildcard "*" to clear the entire document
 *
 * The input can be an associative array or a stdClass-like object.
 * Intermediate paths are ensured to exist before deletion.
 *
 * @param array|object $document  The data source (array or object) to operate on.
 * @param string       $key       The key path to delete (e.g. "foo.bar" or "foo.*" or "*").
 * @param string       $separator The separator used to split the key path. Defaults to '.'.
 * @param bool|null    $isArray   Optional: force array (`true`) or object (`false`) mode; if `null`, auto-detects.
 *
 * @return array|object The updated document after deletion.
 *
 * @throws InvalidArgumentException If input is not array/object, if key is invalid,
 *                                  or if path traversal encounters a type mismatch.
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 *
 * @example
 * Delete an array key :
 * ```php
 * $doc = ['name' => 'Alice', 'age' => 30];
 * $doc = deleteKeyValue( $doc , 'age' ) ;
 * // Result: ['name' => 'Alice']
 * ```
 *
 * Delete an array complex key expression 'user.name" :
 * ```php
 * $doc = ['user' => ['name' => 'Alice', 'email' => 'a@b.c']];
 * $doc = deleteKeyValue($doc, 'user.name');
 * // Result: ['user' => ['email' => 'a@b.c']]
 * ```
 *
 * Deletes all object properties :
 * ```php
 * $doc = (object)['x' => 1, 'y' => 2];
 * $doc = deleteKeyValue($doc, '*');
 * // Result: (object)[]
 * ```
 *
 * Delete all properties with a wildcard key expression.
 * ```php
 * $doc = ['meta' => ['a' => 1, 'b' => 2]];
 * $doc = deleteKeyValue($doc, 'meta.*');
 * // Result: ['meta' => []]
 * ```
 *
 * @example
 * ```php
 * $doc = (object)['user' => (object)['name' => 'Alice', 'email' => 'a@b.c']];
 * $doc = deleteKeyValue($doc, 'user.*');
 * // Result: (object)['user' => (object)[]]
 * ```
 */
function deleteKeyValue
(
    array|object $document ,
          string $key ,
          string $separator = '.' ,
           ?bool $isArray   = null
)
:array|object
{
    $isArray = assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;

    if ( $key === '*' )
    {
        if ( $isArray )
        {
            return [] ;
        }

        foreach ( get_object_vars( $document ) as $prop => $_ )
        {
            unset( $document->{ $prop } ) ;
        }

        return $document;
    }

    $isWildcard = str_ends_with( $key , $separator . '*' ) ;
    if ( $isWildcard )
    {
        $targetKey = substr( $key , 0 , -2 ) ; // remove ".*"
        $keys      = explode($separator, $targetKey);
        $target    = &resolveReferencePath($document , $keys , $isArray ) ;
        $lastKey   = end($keys ) ;

        if ($isArray)
        {
            if ( isset( $target[ $lastKey ] ) && is_array( $target[ $lastKey ] ) )
            {
                $target[ $lastKey ] = [] ;
            }
        }
        else
        {
            if ( isset( $target->{ $lastKey }) && is_object( $target->{ $lastKey } ) )
            {
                foreach ( get_object_vars( $target->{ $lastKey } ) as $prop => $_ )
                {
                    unset( $target->{ $lastKey }->{ $prop } ) ;
                }
            }
        }

        return $document;
    }

    if ( !str_contains( $key , $separator ) )
    {
        if ( $isArray )
        {
            unset( $document[ $key ] );
        }
        else
        {
            unset( $document->{ $key } ) ;
        }

        return $document ;
    }

    $keys    = explode($separator, $key);
    $parent  = &resolveReferencePath( $document , $keys , $isArray ) ;
    $lastKey = end($keys ) ;

    if ($isArray)
    {
        unset( $parent[ $lastKey ] );
    }
    else
    {
        unset( $parent->{$lastKey});
    }

    return $document ;
}