<?php

namespace oihana\core\arrays ;

use InvalidArgumentException;
use oihana\core\options\CompressOption;
use function oihana\core\objects\compress as compressObject ;

/**
 * Compress the given array by removing entries that match specified conditions.
 *
 * This function traverses an array and removes elements according to the provided
 * configuration. It can work recursively on nested arrays and objects, with optional
 * depth limitation and key-based exclusions.
 *
 * Useful for cleaning associative arrays (e.g., form data, object exports) by removing
 * nulls, empty strings, or other unwanted values.
 *
 * @param array $array The input array to compress.
 * @param array{
 *     clone?: bool ,                     // If true, operate on a cloned copy. Default: false.
 *     conditions?: callable|callable[] , // One or more callbacks: fn(mixed $value): bool.
 *                                        // If any returns true, the entry is removed.
 *                                        // Default: [fn($v) => is_null($v)].
 *     excludes?: string[] ,              // Keys to exclude from filtering, even if matched.
 *     recursive?: bool ,                 // Whether to compress nested arrays/objects. Default: false.
 *     depth?: int|null ,                 // Maximum recursion depth. null = unlimited.
 *     removeKeys?: string[] ,            // Keys to always remove from the array.
 *     throwable?: bool                   // If true, throws InvalidArgumentException on invalid callbacks. Default: true.
 * }|null $options Optional configuration.
 * @param int $currentDepth Internal counter used to track recursion depth.
 *
 * @return array The compressed array (or its clone if `clone=true`).
 *
 * @throws InvalidArgumentException If invalid callbacks are provided and 'throwable' is true.
 *
 * @example Basic cleanup of null values
 * ```php
 * use function oihana\core\arrays\compress;
 *
 * $data = [ 'id' => 1 , 'name' => 'hello' , 'desc' => null ];
 * $clean = compress($data);
 *
 * // Result: [ 'id' => 1 , 'name' => 'hello' ]
 * ```
 *
 * @example Excluding a key from filtering
 * ```php
 * $data = [ 'id' => 1 , 'name' => null ];
 * $clean = compress($data, [ 'excludes' => ['name'] ]);
 *
 * // Result: [ 'id' => 1 , 'name' => null ]
 * ```
 *
 * @example Removing null or empty strings
 * ```php
 * $data = [ 'a' => '' , 'b' => 0 , 'c' => null ];
 *
 * $clean = compress($data, [
 *     'conditions' => [
 *         fn($v) => $v === null,
 *         fn($v) => is_string($v) && $v === ''
 *     ]
 * ]);
 *
 * // Result: [ 'b' => 0 ]
 * ```
 *
 * @example Recursive compression with depth limit
 * ```php
 * $nested = [
 *     'id'   => 2,
 *     'meta' => [ 'created' => null, 'tags' => [] ],
 * ];
 *
 * $clean = compress($nested, [
 *     'conditions' => fn($v) => $v === [] || $v === null,
 *     'recursive'  => true,
 *     'depth'      => 1
 * ]);
 *
 * // Result: [ 'id' => 2 ]
 * ```
 *
 * @example Mixing arrays and objects
 * ```php
 * use function oihana\core\objects\compress as compressObject;
 *
 * $data = [
 *     'user' => (object)[ 'id' => 1 , 'tmp' => null ],
 *     'tags' => [ 'a' => null , 'b' => 'keep' ],
 * ];
 *
 * $clean = compress($data, [
 *     'conditions' => fn($v) => $v === null,
 *     'recursive'  => true,
 * ]);
 *
 * // Result: [ 'user' => { "id":1 }, 'tags' => [ 'b' => 'keep' ] ]
 * ```
 *
 * @example Cloning input before compression
 * ```php
 * $original = [ 'x' => null , 'y' => 5 ];
 * $copy     = compress($original, [ 'clone' => true ]);
 *
 * // $original remains unchanged
 * // $copy == [ 'y' => 5 ]
 * ```
 *
 * @package oihana\core\arrays
 * @author  Marc Alcaraz
 * @since   1.0.0
 */
function compress( array $array , ?array $options = [], int $currentDepth = 0 ): array
{
    $options = CompressOption::normalize($options);

    $clone      = $options[ CompressOption::CLONE       ] ;
    $conditions = $options[ CompressOption::CONDITIONS  ] ;
    $excludes   = $options[ CompressOption::EXCLUDES    ] ;
    $maxDepth   = $options[ CompressOption::DEPTH       ] ;
    $recursive  = $options[ CompressOption::RECURSIVE   ] ;
    $removeKeys = $options[ CompressOption::REMOVE_KEYS ] ;

    $array = $clone ? [ ...$array ] : $array ;

    foreach ( $array as $key => $value )
    {
        if ( is_array( $removeKeys ) && in_array( $key , $removeKeys , true ) )
        {
            unset( $array[ $key ] );
            continue;
        }

        if ( is_array( $excludes ) && in_array( $key , $excludes , true ) )
        {
            continue;
        }

        if ( is_object( $value ) && $recursive && ( $maxDepth === null || $currentDepth < $maxDepth ) )
        {
            $array[ $key ] = compressObject( $value , $options , $currentDepth + 1) ;
            continue;
        }

        if( is_array( $value ) && $recursive && ($maxDepth === null || $currentDepth < $maxDepth))
        {
            $array[ $key ] = compress( $value , $options , $currentDepth + 1 ) ;
            continue;
        }

        foreach ( $conditions as $condition )
        {
            if ( $condition( $value ) )
            {
                unset( $array[ $key ] ) ;
                break ;
            }
        }
    }

    if ( hasIntKeys( $array ) )
    {
        $array = array_values( $array ) ;
    }

    return $array;
}