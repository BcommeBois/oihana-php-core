<?php

declare(strict_types=1);

namespace oihana\core\accessors;

use InvalidArgumentException;

/**
 * Deletes a value from an array or object using a dot-notated key path.
 *
 * This utility supports:
 * - Single key deletion (string)
 * - Multiple key deletion (array of strings)
 * - Flat keys (e.g., "name")
 * - Nested keys (e.g., "user.profile.name")
 * - Wildcard deletion for sub-containers (e.g., "user.*")
 * - Global wildcard "*" to clear the entire document
 *
 * The input can be an associative array or a stdClass-like object.
 * Intermediate paths are ensured to exist before deletion.
 *
 * @param array|object $document  The data source (array or object) to operate on.
 * @param string|array $key       The key path(s) to delete (e.g. "foo.bar" or ["foo.bar", "baz.*"]).
 * @param string       $separator The separator used to split the key path. Defaults to '.'.
 * @param bool|null    $isArray   Optional: force array (`true`) or object (`false`) mode; if `null`, auto-detects.
 * @param bool         $strict    If true, throws exception when key doesn't exist. Default: false.
 *
 * @return array|object The updated document after deletion.
 *
 * @throws InvalidArgumentException If input is not array/object, if key is invalid,
 *                                  or if path traversal encounters a type mismatch.
 *                                  In strict mode, also throws if key doesn't exist.
 *

 * @example
 * Delete an single array key :
 * ```php
 * $doc = ['name' => 'Alice', 'age' => 30];
 * $doc = deleteKeyValue( $doc , 'age' ) ;
 * // Result: ['name' => 'Alice']
 * ```
 *
 * @example
 * Delete multiple keys at once:
 * ```php
 * $doc = ['name' => 'Alice', 'age' => 30, 'email' => 'a@b.c'];
 * $doc = deleteKeyValue($doc, ['age', 'email']);
 * // Result: ['name' => 'Alice']
 * ```
 *
 * @example
 * Delete multiple nested keys:
 * ```php
 * $doc = ['user' => ['name' => 'Alice', 'email' => 'a@b.c', 'age' => 30]];
 * $doc = deleteKeyValue($doc, ['user.name', 'user.age']);
 * // Result: ['user' => ['email' => 'a@b.c']]
 * ```
 *
 * @example
 * Mix of simple and wildcard deletions:
 * ```php
 * $doc =
 * [
 *     'name' => 'Alice',
 *     'meta' => ['a' => 1, 'b' => 2],
 *     'config' => ['x' => 10, 'y' => 20]
 * ];
 * $doc = deleteKeyValue($doc, ['name', 'meta.*']);
 * // Result: ['meta' => [], 'config' => ['x' => 10, 'y' => 20]]
 *
 * @example
 * Delete all properties with a wildcard key expression.
 * ```php
 * $doc = ['meta' => ['a' => 1, 'b' => 2]];
 * $doc = deleteKeyValue($doc, 'meta.*');
 * // Result: ['meta' => []]
 * ```
 *
 * @example
 * Delete all object properties:
 * ```php
 * $doc = (object)['user' => (object)['name' => 'Alice', 'email' => 'a@b.c']];
 * $doc = deleteKeyValue($doc, 'user.*');
 * // Result: (object)['user' => (object)[]]
 * ```
 *
 * @example
 * Non-strict mode (default) - ignore missing keys:
 * ```php
 * $doc = ['name' => 'Alice', 'age' => 30];
 * $doc = deleteKeyValue($doc, ['age', 'email', 'phone']); // no error
 * // Result: ['name' => 'Alice']
 * ```
 *
 * @example
 * Strict mode - throws exception for missing keys:
 * ```php
 * $doc = ['name' => 'Alice', 'age' => 30];
 * try {
 * $doc = deleteKeyValue($doc, ['age', 'email'], strict: true);
 * } catch (InvalidArgumentException $e) {
 * echo $e->getMessage(); // "Key 'email' does not exist."
 * }
 * ```
 *
 * @package oihana\core\accessors
 * @author  Marc Alcaraz (ekameleon)
 * @since   1.0.0
 */
function deleteKeyValue
(
    array|object $document ,
    string|array $key ,
    string       $separator = '.' ,
    ?bool        $isArray   = null ,
    bool         $strict    = false
)
:array|object
{
    if ( is_array( $key ) )
    {
        foreach ( $key as $singleKey )
        {
            if ( !is_string( $singleKey ) )
            {
                throw new InvalidArgumentException('All keys must be strings.' ) ;
            }
            $document = deleteKeyValue( $document , $singleKey , $separator , $isArray  , $strict ) ;
        }
        return $document;
    }

    $isArray = assertDocumentKeyValid( $document , $key , $separator , $isArray ) ;

    if ( $strict && $key !== '*' && !str_ends_with( $key , $separator . '*' ) )
    {
        if ( !hasKeyValue( $document , $key , $separator , $isArray ) )
        {
            throw new InvalidArgumentException(sprintf("Key '%s' does not exist.", $key ) ) ;
        }
    }

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

        if ( $strict && !hasKeyValue( $document , $targetKey , $separator , $isArray ) )
        {
            throw new InvalidArgumentException(sprintf("Key '%s' does not exist.", $targetKey));
        }

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

    if ( is_array($parent) )
    {
        unset( $parent[ $lastKey ] );
    }
    else
    {
        unset( $parent->{ $lastKey } ) ;
    }

    return $document ;
}